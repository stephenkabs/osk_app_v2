<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\PropertyLeaseAgreement;
use App\Models\PropertyPayment;
use Carbon\Carbon;
use App\Services\WirepickService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;   // ✅ For sending requests to Make
use Illuminate\Support\Facades\Auth;

use Barryvdh\DomPDF\Facade\Pdf;


use Illuminate\Http\Request;

class PropertyPaymentsController extends Controller
{
    /**
     * Landlord payments list
     */
    public function index(Property $property)
    {
        $payments = PropertyPayment::with(['lease.unit','tenant'])
            ->where('property_id', $property->id)
            ->latest('payment_date')
            ->paginate(20);

        $leases = PropertyLeaseAgreement::with(['tenant','unit'])
            ->where('property_id', $property->id)
            ->where('status', 'active')
            ->get();



        return view('properties.payments.index', compact(
            'property',
            'payments',
            'leases'
        ));
    }



    public function store(Request $request, Property $property)
{
    $data = $request->validate([
        'lease_id'      => ['required','exists:property_lease_agreements,id'],
        'payment_month' => ['required','date_format:Y-m'],
        'amount'        => ['required','numeric','min:0.01'],
        'method'        => ['nullable','string'],
        'payment_date'  => ['required','date'],
        'reference'     => ['nullable','string','max:190'],
    ]);

    $lease = PropertyLeaseAgreement::with('tenant')
        ->where('property_id', $property->id)
        ->findOrFail($data['lease_id']);

    if (!$lease->tenant) {
        return back()->withErrors([
            'lease_id' => 'This lease has no tenant assigned.'
        ]);
    }

    $rentDue = $this->rentDueForMonth($lease, $data['payment_month']);

    $paidSoFar = PropertyPayment::where('lease_agreement_id', $lease->id)
        ->where('payment_month', $data['payment_month'])
        ->sum('amount');

    $newTotal = $paidSoFar + $data['amount'];

    $status = match (true) {
        $newTotal >= $rentDue => 'paid',
        $newTotal > 0         => 'partial',
        default               => 'pending',
    };

    $payment = PropertyPayment::create([
        'property_id'        => $property->id,
        'lease_agreement_id' => $lease->id,
        'user_id'            => $lease->tenant->id,
        'payment_month'      => $data['payment_month'],
        'payment_date'       => $data['payment_date'],
        'amount'             => $data['amount'],
        'method'             => $data['method'] ?? 'cash',
        'reference'          => $data['reference'],
        'status'             => $status,
        'recorded_by'        => auth()->id(),
    ]);

    // 🚫 Do not sync unpaid rent
    if ($status !== 'paid') {
        return back()->with('success', 'Payment recorded successfully.');
    }

    // 🚀 PUSH TO QUICKBOOKS
    try {

        $payloadQB = [
            'type'        => 'rent_payment',
            'payment_id'  => $payment->id,
            'amount'      => $payment->amount,
            'currency'    => 'ZMW',
            'payment_date'=> $payment->payment_date,
            'reference'   => $payment->reference,

            'tenant' => [
                'id'    => $lease->tenant->id,
                'name'  => $lease->tenant->name,
                'email' => $lease->tenant->email,
                'qbo_customer_id' => $lease->tenant->quickbooks_customer_id,
            ],

            'property' => [
                'id'          => $property->id,
                'name'        => $property->property_name,
                'qbo_item_id' => $property->qbo_item_id,
            ],

            'lease' => [
                'id'    => $lease->id,
                'month' => $data['payment_month'],
                'rent'  => $rentDue,
            ],
        ];

        $url = config('services.make.webhook_url_qb_rent_payment');

        if ($url) {
            Http::timeout(20)->acceptJson()->post($url, $payloadQB);
        }

    } catch (\Throwable $e) {
        Log::error("QuickBooks rent sync failed", [
            'payment_id' => $payment->id,
            'error'      => $e->getMessage(),
        ]);
    }

    return back()->with('success', 'Payment recorded & synced to QuickBooks.');
}




public function receipt(Property $property, PropertyLeaseAgreement $lease, string $month)
{
    // 🔒 Safety checks
    abort_unless($lease->property_id === $property->id, 404);

    // Month parsing (YYYY-MM)
    try {
        $monthDate = Carbon::createFromFormat('Y-m', $month);
    } catch (\Exception $e) {
        abort(404, 'Invalid month.');
    }

    // All payments for that lease + month
    $payments = PropertyPayment::where('property_id', $property->id)
        ->where('lease_agreement_id', $lease->id)
        ->where('payment_month', $month)
        ->orderBy('payment_date')
        ->get();

    if ($payments->isEmpty()) {
        abort(404, 'No payments found for this month.');
    }

    // Totals
    $paidTotal = $payments->sum('amount');

    $monthlyRent = (float) (
        $lease->rent_amount ??
        optional($lease->unit)->rent_amount ??
        0
    );

    $balance = max($monthlyRent - $paidTotal, 0);

    // Generate PDF
    $pdf = Pdf::loadView('properties.payments.receipt', [
        'property'     => $property,
        'lease'        => $lease,
        'tenant'       => $lease->tenant,
        'unit'         => $lease->unit,
        'payments'     => $payments,
        'month'        => $monthDate,
        'monthlyRent'  => $monthlyRent,
        'paidTotal'    => $paidTotal,
        'balance'      => $balance,
    ])->setPaper('a4');

    $filename = 'Rent-Receipt-' .
        ($lease->tenant->name ?? 'Tenant') . '-' .
        $monthDate->format('Y-m');

    return $pdf->download($filename . '.pdf');
}


    /**
     * Store a rent payment (manual recording)
     */
    // public function store(Request $request, Property $property)
    // {
    //     $data = $request->validate([
    //       'lease_id' => ['required','exists:property_lease_agreements,id'],
    //         'payment_month' => ['required','date_format:Y-m'],
    //         'amount'        => ['required','numeric','min:0.01'],
    //         'method'        => ['nullable','string'],
    //         'payment_date'  => ['required','date'],
    //         'reference'     => ['nullable','string','max:190'],
    //     ]);

    //     /** @var PropertyLeaseAgreement $lease */
    //     $lease = PropertyLeaseAgreement::with('tenant')
    //         ->where('property_id', $property->id)
    //         ->findOrFail($data['lease_id']);

    //     if (!$lease->tenant) {
    //         return back()->withErrors([
    //             'lease_id' => 'This lease has no tenant assigned.'
    //         ]);
    //     }

    //     // ✅ Calculate rent due for the selected month
    //     $rentDue = $this->rentDueForMonth($lease, $data['payment_month']);

    //     // ✅ Already paid this month
    //     $paidSoFar = PropertyPayment::where('lease_agreement_id', $lease->id)
    //         ->where('payment_month', $data['payment_month'])
    //         ->sum('amount');

    //     $newTotal = $paidSoFar + $data['amount'];

    //     // ✅ Payment status
    //     $status = match (true) {
    //         $newTotal >= $rentDue => 'paid',
    //         $newTotal > 0         => 'partial',
    //         default               => 'pending',
    //     };

    //     PropertyPayment::create([
    //         'property_id'   => $property->id,
    //         'lease_agreement_id'  => $lease->id,   // ✅ FIX
    //         'user_id'       => $lease->tenant->id,
    //         'payment_month' => $data['payment_month'],
    //         'payment_date'  => $data['payment_date'],
    //         'amount'        => $data['amount'],
    //         'method'        => $data['method'] ?? 'cash',
    //         'reference'     => $data['reference'],
    //         'status'        => $status,
    //         'recorded_by'   => auth()->id(),
    //     ]);

    //     return back()->with('success', 'Payment recorded successfully.');
    // }




    protected function rentDueForMonth(
        PropertyLeaseAgreement $lease,
        string $paymentMonth
    ): float {
        $startDate   = Carbon::parse($lease->start_date);
        $targetMonth = Carbon::createFromFormat('Y-m', $paymentMonth)->startOfMonth();

        // ✅ First lease month → PRORATED
        if ($targetMonth->isSameMonth($startDate)) {
            return $this->calculateProratedAmount(
                (float) $lease->rent_amount,
                $startDate
            );
        }

        // ✅ All other months → FULL rent
        return (float) $lease->rent_amount;
    }

    /**
     * Prorate first month rent
     */
    protected function calculateProratedAmount(
        float $monthlyRent,
        Carbon $startDate
    ): float {
        $daysInMonth   = $startDate->daysInMonth;
        $dayOfMonth    = $startDate->day;
        $daysRemaining = ($daysInMonth - $dayOfMonth) + 1;

        $dailyRate = $monthlyRent / $daysInMonth;

        return round($dailyRate * $daysRemaining, 2);
    }


    public function edit(Property $property, PropertyPayment $payment)
{
    abort_unless($payment->property_id === $property->id, 404);

    $payment->load(['lease.unit', 'tenant']);

    return view('properties.payments.edit', compact(
        'property',
        'payment'
    ));
}
public function update(
    Request $request,
    Property $property,
    PropertyPayment $payment
) {
    abort_unless($payment->property_id === $property->id, 404);

    $data = $request->validate([
        'amount'       => ['required','numeric','min:0.01'],
        'payment_date' => ['required','date'],
        'method'       => ['nullable','string'],
        'reference'    => ['nullable','string','max:190'],
    ]);

    $payment->update($data);

    return redirect()
        ->route('property.payments.index', $property->slug)
        ->with('success', 'Payment updated successfully.');
}

public function destroy(Property $property, PropertyPayment $payment)
{
    abort_unless($payment->property_id === $property->id, 404);

    $payment->delete();

    return back()->with('success', 'Payment deleted.');
}

private function normalizeMsisdn(string $phone): string
{
    $phone = preg_replace('/\D/', '', $phone);

    if (str_starts_with($phone, '0')) {
        return '260' . substr($phone, 1);
    }

    if (str_starts_with($phone, '260')) {
        return $phone;
    }

    throw new \InvalidArgumentException('Invalid phone number format');
}

public function payWithMobileMoney(
    Request $request,
    Property $property,
    WirepickService $wirepick
) {
    // ---------------------------------------------
    // 1️⃣ Validate request
    // ---------------------------------------------
    $request->validate([
        'lease_agreement_id' => 'required|exists:property_lease_agreements,id',
        'payment_month'      => 'required|date_format:Y-m',
        'amount'             => 'required|numeric|min:1',
        'phone'              => 'required',
        'gateway'            => 'required|in:airtel,mtn',
    ]);

    $user = Auth::user();

    // ---------------------------------------------
    // 2️⃣ Create local payment record (PENDING)
    // ---------------------------------------------
    $payment = PropertyPayment::create([
        'property_id'        => $property->id,
        'lease_agreement_id' => $request->lease_agreement_id,
        'user_id'            => $user->id,
        'payment_date'       => now(),
        'payment_month'      => $request->payment_month,
        'amount'             => (float) $request->amount,
        'method'             => 'mobile_money',
        'status'             => 'pending',
        'gateway'            => $request->gateway,
        'gateway_status'     => 'initiated',
        'recorded_by'        => $user->id,
    ]);

    // ---------------------------------------------
    // 3️⃣ Build Wirepick payload (EXACT format)
    // ---------------------------------------------
 $payload = [
    "msisdn"      => $this->normalizeMsisdn($request->phone),
    "amount"      => (float) $payment->amount,
    "reference"   => "RENT-{$payment->id}",
    "narration"   => "Rent payment {$payment->payment_month}",
    "environment" => "UAT",
    "currency"    => "ZMW",
    "gateway"     => strtoupper($request->gateway), // API expects caps
    "action"      => "collect",
];

$response = $wirepick->pay($payload, $request->gateway); // service expects lowercase

    Log::info("WIREPICK RENT REQUEST", $payload);

    // ---------------------------------------------
    // 4️⃣ Send to Wirepick
    // ---------------------------------------------
    try {
        $response = $wirepick->pay($payload, strtoupper($request->gateway));
    } catch (\Throwable $e) {
        Log::error("WIREPICK RENT ERROR", [
            'error' => $e->getMessage()
        ]);

        return back()->withErrors([
            'payment' => 'Unable to connect to payment gateway. Please try again.'
        ]);
    }

    Log::info("WIREPICK RENT RESPONSE", $response);

    // ---------------------------------------------
    // 5️⃣ Persist gateway response
    // ---------------------------------------------
    $payment->update([
        'gateway_reference' => $response['gw_id'] ?? null,
        'gateway_payload'   => $response,
        'gateway_status'    => $response['status'] ?? 'unknown',
    ]);

    // ---------------------------------------------
    // 6️⃣ Interpret Wirepick status (IMPORTANT)
    // ---------------------------------------------
    $wpStatus = strtoupper($response['status'] ?? '');

  if (in_array($wpStatus, ['SUCCESSFUL', 'ACCEPTED'])) {

        // ✅ This is SUCCESS for mobile money
        return back()->with(
            'success',
            'Payment request sent. Please approve the payment on your phone.'
        );
    }

    // ---------------------------------------------
    // 7️⃣ Failure handling
    // ---------------------------------------------
    $payment->update([
        'status' => 'failed'
    ]);

    return back()->withErrors([
        'payment' => $response['message'] ?? 'Mobile money request failed.'
    ]);
}


public function process(Request $request, WirepickService $wp)
{
    $request->validate([
        'msisdn' => 'required',
        'amount' => 'required|min:1',
        'method' => 'required'
    ]);



        $user = $request->user();
    $partner = $user?->partner;

    if (!$partner || $partner->status !== 'approved') {
        return response()->json(['message' => 'You must be an approved partner.'], 422);
    }

    $property = Property::findOrFail($request->property_id);
    $amount   = floatval($request->amount);
    $method   = $request->method;

    $shares = $amount / $property->qbo_unit_price;

    $reference = uniqid();

    $payload = [
        "msisdn"      => $request->msisdn,
        "amount"      => floatval($request->amount),
        "reference"   => $reference,
        "narration"   => "Payment via Wirepick",
        "environment" => "UAT",
        "currency"    => "ZMW",
        "gateway"     => strtoupper($request->method),
        "action"      => "collect",
    ];

    // If paying by card, validate + encrypt customer card info
    if ($request->method === 'card') {

        $request->validate([
            'pan'            => 'required',
            'expiry'         => 'required',
            'cvv'            => 'required',
            'cardholderName' => 'required',
        ]);

        $payload['b64EncrCardInfo'] = $wp->encryptCard([
            "pan"            => $request->pan,
            "expiry"         => $request->expiry,
            "cvv"            => $request->cvv,
            "cardholderName" => $request->cardholderName
        ]);
    }




    $response = $wp->pay($payload, $request->method);

    // ---------------------------------------------
// 3DS / OTP Authentication Handling
// ---------------------------------------------
if (($response['code'] ?? null) === '102' || ($response['status'] ?? '') === 'AWAIT_3DS') {

    // Redirect user to Wirepick 3DS authentication page
    if (!empty($response['otpUrl'])) {
        return redirect()->away($response['otpUrl']);
    }

    // Fallback error
    return response()->json([
        "status"  => "AWAIT_3DS",
        "message" => "OTP authentication required but no redirect URL provided.",
        "data"    => $response
    ], 422);
}

    if (!empty($response['error']) && $response['type'] === 'timeout') {
    return response()->view('wirepick.timeout', [
        "message" => $response["message"],
        "retryUrl" => url()->previous()
    ]);
}

    if (!isset($response['status'])) {
        return response()->json([
            "status"   => "failed",
            "message"  => $wirepickResponse['message'] ?? "Wirepick error.",
            "wirepick" => $response,
        ], 422);
    }

// Normalize Wirepick status
$wpStatus = strtoupper($response['status']);

// Decide status for database
if ($wpStatus === 'SUCCESSFUL' || 'ACCEPTED') {
    $dbStatus = 'paid';
} elseif ($wpStatus === 'FAILED') {
    return response()->json([
        "status"   => "failed",
        "message"  => $response['message'] ?? "Wirepick payment failed.",
        "wirepick" => $response,
    ], 422);
} else {
    // PENDING, PURCHASED, etc.
    $dbStatus = 'pending';
}

// ---------------------------------------------
//  CREATE PAYMENT RECORD
// ---------------------------------------------
$payment = Payment::create([
    'partner_id'        => $partner->id,
    'property_id'       => $property->id,
    'amount'            => $amount,
    'method'            => $method,
    'reference'         => $response['reference'] ?? $reference,
    'status'            => $dbStatus,
    'currency'          => 'ZMW',
    'my_total_shares'   => $shares,
]);

// STOP HERE IF NOT SUCCESSFUL
if ($dbStatus !== 'paid') {
    return view('wirepick.result', compact('payload', 'response'));
}

// ---------------------------------------------
//  AUTO-CONVERT → payment_completed FOR QBO SYNC
// ---------------------------------------------
$payment->status = 'payment_completed';
$payment->save();

// ---------------------------------------------
//  STOCK (SHARES) DECREMENT
// ---------------------------------------------
if ($property && $shares > 0) {
    $before = $property->qbo_qty_on_hand;

    $property->decrement('qbo_qty_on_hand', $shares);
    $property->refresh();

    Log::info("Property {$property->id} stock decremented: {$before} → {$property->qbo_qty_on_hand} (by {$shares})");
}

// ---------------------------------------------
//  PUSH TO QUICKBOOKS (Make.com Webhook)
// ---------------------------------------------
try {
    $payloadQB = [
        'quickbooks_customer_id' => $partner->quickbooks_customer_id,
        'amount'                 => $payment->amount,
        'currency'               => $payment->currency,
        'payment_id'             => $payment->id,
        'property'               => [
            'id'            => $property->id,
            'name'          => $property->property_name,
            'qbo_item_id'   => $property->qbo_item_id,
            'unit_price'    => $property->qbo_unit_price,
            'qty_sold'      => $shares,
            'qty_remaining' => $property->qbo_qty_on_hand,
        ],
    ];

    $url = config('services.make.webhook_url_qb_sales_receipt');

    if ($url) {
        Http::timeout(20)->acceptJson()->post($url, $payloadQB);
        Log::info("QuickBooks SalesReceipt Auto-Sent for payment {$payment->id}", $payloadQB);
    } else {
        Log::warning("QuickBooks webhook URL not configured.");
    }

} catch (\Throwable $e) {
    Log::error("QuickBooks auto-sync FAILED for payment {$payment->id}: {$e->getMessage()}");
}

return view('wirepick.result', compact('payload', 'response'))
    ->with('success', 'Payment successful & synced to QuickBooks.');






}




}
