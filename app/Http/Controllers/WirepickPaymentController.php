<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Property;
use App\Models\Payment;
use Illuminate\Support\Facades\Http;   // ✅ For sending requests to Make
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Services\WirepickService;

class WirepickPaymentController extends Controller
{
    public function showForm()
    {
        return view('wirepick.form');
    }


        public function tenantForm()
    {
        return view('wirepick.form_tenant');
    }

        public function tena()
    {
        return view('wirepick.tenant_form');
    }

// WirepickPaymentController.php


public function checkout(Property $property)
{
    $user = auth()->user();
    $partner = $user->partner;

    if (!$partner || $partner->status !== 'approved') {
        return redirect()
            ->route('partners.create')
            ->with('warning', 'Please complete and approve your investor profile.');
    }

    return view('wirepick.checkout', [
        'property' => $property,
        'partner'  => $partner,
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



public function trying(Request $request, WirepickService $wp)
{
    $request->validate([
        'msisdn' => 'required',
        'amount' => 'required|min:1',
        'method' => 'required'
    ]);



        $user = $request->user();


    $property = Property::findOrFail($request->property_id);
    $amount   = floatval($request->amount);
    $method   = $request->method;


    $reference = uniqid();

    $payload = [
        "msisdn"      => $request->msisdn,
        "amount"      => floatval($request->amount),
        "reference"   => $reference,
        "narration"   => "Payment via Wirepick",
        "environment" => "PROD",
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



// STOP HERE IF NOT SUCCESSFUL
if ($dbStatus !== 'paid') {
    return view('wirepick.result', compact('payload', 'response'));
}





return view('wirepick.result', compact('payload', 'response'))
    ->with('success', 'Payment successful & synced to QuickBooks.');


}




}
