<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Services\WirepickService;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;
use App\Mail\PaymentStatusMail;

class PropertyInvestmentController extends Controller
{
    /**
     * Start a Stripe Checkout Session for a property investment.
     */
public function checkout(Request $request)
{
    $user    = $request->user();
    $partner = $user?->partner?->fresh();

    if (!$partner || $partner->status !== 'approved') {
        return $request->expectsJson()
            ? response()->json(['message' => 'You must be an approved partner to invest.'], 422)
            : back()->withErrors(['partner' => 'You must be an approved partner to invest.']);
    }

    if (empty($partner->quickbooks_customer_id)) {
        return $request->expectsJson()
            ? response()->json(['message' => 'Your profile is not yet synced with our accounting system.'], 422)
            : back()->withErrors(['partner' => 'Your profile is not yet synced with our accounting system.']);
    }

    $property = Property::findOrFail($request->property_id);
    $qtyAvailable = (int) $property->qbo_qty_on_hand;
    $unitPrice    = (float) $property->qbo_unit_price;

    if ($qtyAvailable <= 0 || $unitPrice <= 0) {
        return $request->expectsJson()
            ? response()->json(['message' => 'This property is not available for investment.'], 422)
            : back()->withErrors(['cart' => 'This property is not available for investment.']);
    }

    // ✅ Get user-entered amount (fallback to full if missing)
    $amount = (float) $request->input('amount', $unitPrice * $qtyAvailable);
    if ($amount <= 0) {
        return $request->expectsJson()
            ? response()->json(['message' => 'Invalid investment amount.'], 422)
            : back()->withErrors(['cart' => 'Invalid investment amount.']);
    }

    // ✅ Calculate shares based on amount entered
    $shares = $amount / $unitPrice;
    if ($shares > $qtyAvailable) {
        return $request->expectsJson()
            ? response()->json(['message' => "You cannot buy more than {$qtyAvailable} shares."], 422)
            : back()->withErrors(['cart' => "You cannot buy more than {$qtyAvailable} shares."]);
    }

    Stripe::setApiKey(config('services.stripe.secret'));

    $session = StripeSession::create([
        'payment_method_types' => ['card'],
        'line_items' => [[
            'price_data' => [
                'currency'     => 'USD',
                'product_data' => ['name' => "Investment in {$property->property_name}"],
                'unit_amount'  => (int) round($amount * 100), // ✅ use amount entered
            ],
            'quantity' => 1,
        ]],
        'mode'        => 'payment',
        'success_url' => route('property-invest.success') . '?session_id={CHECKOUT_SESSION_ID}',
        'cancel_url'  => route('property-invest.cancel'),
        'metadata'    => [
            'partner_id'             => (string) $partner->id,
            'property_id'            => (string) $property->id,
            'shares'                 => $shares,
            'amount'                 => $amount,
            'quickbooks_customer_id' => $partner->quickbooks_customer_id,
        ],
    ]);

    Payment::create([
        'partner_id'             => $partner->id,
        'property_id'            => $property->id,
        'method'                 => 'card',
        'status'                 => 'pending',
        'stripe_session_id'      => $session->id,
        'amount'                 => $amount, // ✅ save user amount
        'currency'               => 'USD',
        'quickbooks_customer_id' => $partner->quickbooks_customer_id,
        'my_total_shares'        => $shares, // ✅ optional: store shares
    ]);

    // Mail::to($partner->email)->send(new PaymentStatusMail(
    //     $partner,
    //     $amount,
    //     'USD',
    //     'pending'
    // ));

// ✅ Always return JSON if it's AJAX/Fetch
if ($request->ajax() || $request->wantsJson()) {
    return response()->json(['id' => $session->id]);
}

// ✅ Otherwise (form submit via browser), redirect to Stripe
return redirect($session->url);

}



// public function wirepickPayment(Request $request, WirepickService $wp)
// {
//     $user = $request->user();
//     $partner = $user?->partner;

//     if (!$partner || $partner->status !== 'approved') {
//         return response()->json(['message' => 'You must be an approved partner.'], 422);
//     }

//     $property = Property::findOrFail($request->property_id);
//     $amount   = floatval($request->amount);
//     $method   = $request->method;

//     $shares = $amount / $property->qbo_unit_price;

//     // Build payload for Wirepick
//     $payload = [
//         "msisdn"      => $user->phone, // or ask user
//         "amount"      => $amount,
//         "reference"   => uniqid(),
//         "narration"   => "Property Investment",
//         "environment" => "UAT",
//         "currency"    => "ZMW",
//         "gateway"     => strtoupper($method),
//         "action"      => "collect",
//     ];

//     // If CARD, encrypt
//     if ($method === "card") {
//         $payload['b64EncrCardInfo'] = $wp->encryptCard([
//             "pan" => $request->pan,
//             "expiry" => $request->expiry,
//             "cvv" => $request->cvv,
//             "cardholderName" => $request->cardholderName,
//         ]);
//     }

// $wirepickResponse = $wp->pay($payload, $method);

// // Local fallback reference
// $localRef = $payload['reference'];
// $finalRef = $wirepickResponse['reference'] ?? $localRef;

// // Get Wirepick status
// $wpStatus = strtoupper($wirepickResponse['status'] ?? 'UNKNOWN');

// // Decide status to store in DB
// if ($wpStatus === 'SUCCESSFUL') {
//     $dbStatus = 'paid';  // directly mark as paid
// } elseif ($wpStatus === 'FAILED') {
//     // ❌ Do not create a payment record for failed payments
//     return response()->json([
//         "status" => "failed",
//         "message" => "Wirepick payment failed.",
//         "wirepick" => $wirepickResponse,
//     ], 422);
// } else {
//     // PENDING, PURCHASED, or anything else
//     $dbStatus = 'pending';
// }



// // Use our generated reference
// $localRef = $payload['reference'];

// // Use Wirepick's reference ONLY if available
// $finalRef = $wirepickResponse['reference'] ?? $localRef;



//     // Log payment intent
// Payment::create([
//     'partner_id'  => $partner->id,
//     'property_id' => $property->id,
//     'amount'      => $amount,
//     'method'      => $method,
//     'reference'   => $finalRef,
//     'status'      => $dbStatus,  // ✔ dynamic status
//     'currency'    => 'ZMW',
// ]);

//     return response()->json([
//         "status" => "ok",
//         "message" => "Payment request sent.",
//         "wirepick" => $wirepickResponse,
//     ]);
// }

    /**
     * Stripe success callback.
     */


public function wirepickPayment(Request $request, WirepickService $wp)
{
    $user = $request->user();
    $partner = $user?->partner;

    if (!$partner || $partner->status !== 'approved') {
        return response()->json(['message' => 'You must be an approved partner.'], 422);
    }

    $property = Property::findOrFail($request->property_id);
    $amount   = floatval($request->amount);
    $method   = $request->method;

    $shares = $amount / $property->qbo_unit_price;

    // Build payload for Wirepick
    $payload = [
        "msisdn"      => $user->phone,
        "amount"      => $amount,
        "reference"   => uniqid(), // your local reference
        "narration"   => "Property Investment",
        "environment" => "UAT",
        "currency"    => "ZMW",
        "gateway"     => strtoupper($method),
        "action"      => "collect",
    ];

    // If CARD, encrypt
    if ($method === "card") {
        $payload['b64EncrCardInfo'] = $wp->encryptCard([
            "pan"            => $request->pan,
            "expiry"         => $request->expiry,
            "cvv"            => $request->cvv,
            "cardholderName" => $request->cardholderName,
        ]);
    }

    // Call Wirepick
    $wirepickResponse = $wp->pay($payload, $method);

    // LOCAL fallback reference
    $localRef = $payload['reference'];

    // FINAL reference to store
    $finalRef = $wirepickResponse['reference'] ?? $localRef;

    /**
     * ---------------------------------------
     *  FIXED ERROR HANDLING
     * ---------------------------------------
     * When Wirepick token expires OR returns error:
     * Response Example:
     * { "message": "The incoming token has expired" }
     *
     * There is NO "status" field → so DO NOT save payment
     */
    if (!isset($wirepickResponse['status'])) {
        return response()->json([
            "status"   => "failed",
            "message"  => $wirepickResponse['message'] ?? "Wirepick error.",
            "wirepick" => $wirepickResponse,
        ], 422);
    }

    // Normalize Wirepick status
    $wpStatus = strtoupper($wirepickResponse['status']);

    // Decide status for database
    if ($wpStatus === 'SUCCESSFUL') {
        $dbStatus = 'paid';
    } elseif ($wpStatus === 'FAILED') {
        return response()->json([
            "status"   => "failed",
            "message"  => $wirepickResponse['message'] ?? "Wirepick payment failed.",
            "wirepick" => $wirepickResponse,
        ], 422);
    } else {
        // PENDING, PURCHASED, etc.
        $dbStatus = 'pending';
    }

    // Save payment to DB
    Payment::create([
        'partner_id'  => $partner->id,
        'property_id' => $property->id,
        'amount'      => $amount,
        'method'      => $method,
        'reference'   => $finalRef,
        'status'      => $dbStatus,
        'currency'    => 'ZMW',
    ]);

    return response()->json([
        "status"   => "ok",
        "message"  => "Payment request sent.",
        "wirepick" => $wirepickResponse,
    ]);
}












    public function success(Request $request)
{
    $sessionId = $request->query('session_id');
    if (!$sessionId) {
        return redirect()->route('properties.index')->with('error', 'Missing payment session.');
    }

    Stripe::setApiKey(config('services.stripe.secret'));

    try {
        $session = \Stripe\Checkout\Session::retrieve($sessionId);
    } catch (\Throwable $e) {
        Log::error("Stripe Session retrieve failed: " . $e->getMessage());
        return redirect()->route('properties.index')->with('error', 'Could not verify payment.');
    }

    if (($session->payment_status ?? null) !== 'paid') {
        return redirect()->route('properties.index')->with('error', 'Payment not completed.');
    }

    // ✅ Load partner from auth
    $partner = auth()->user()->partner ?? null;
    if (!$partner) {
        return redirect()->route('properties.index')->with('error', 'Partner account not found.');
    }

    // ✅ Block payment if not synced with QBO
    if (empty($partner->quickbooks_customer_id)) {
        Log::error("Partner {$partner->id} missing quickbooks_customer_id during payment success.");
        return redirect()->route('properties.index')->withErrors([
            'partner' => 'Your account is not synced with our accounting system. Please try again after approval.'
        ]);
    }

    // ✅ Update payment status
    $payment = Payment::where('stripe_session_id', $sessionId)->first();
    if ($payment) {
        $payment->update([
            'status'                 => 'paid',
            'quickbooks_customer_id' => $partner->quickbooks_customer_id,
        ]);
    }

    // ✅ Decrement local property shares
    $property = null;
    $shares  = (float) ($session->metadata->shares ?? 0);

    if (!empty($session->metadata->property_id)) {
        $property = Property::find($session->metadata->property_id);

        if ($property && $shares > 0) {
            $before = $property->qbo_qty_on_hand;
            $property->decrement('qbo_qty_on_hand', $shares);
            $property->refresh(); // ✅ reload fresh value from DB

            Log::info("Property {$property->id} stock decremented: {$before} → {$property->qbo_qty_on_hand} (by {$shares})");
        } else {
            Log::warning("No decrement applied: property_id={$session->metadata->property_id}, shares={$shares}");
        }
    }

    // ✅ Push to QuickBooks via Make.com
    if ($payment && $property) {
        try {
            $payload = [
                'quickbooks_customer_id' => $partner->quickbooks_customer_id,
                'amount'                 => $payment->amount,
                'currency'               => $payment->currency,
                'payment_id'             => $payment->id,
                'property'               => [
                    'id'          => $property->id,
                    'name'        => $property->property_name,
                    'qbo_item_id' => $property->qbo_item_id,
                    'unit_price'  => $property->qbo_unit_price,
                    'qty_sold'    => $shares,
                    'qty_remaining' => $property->qbo_qty_on_hand,
                ],
            ];

            $url = config('services.make.webhook_url_qb_sales_receipt');
            if ($url) {
                Http::timeout(20)->acceptJson()->post($url, $payload);
                Log::info("QuickBooks SalesReceipt payload sent for payment {$payment->id}", $payload);
            } else {
                Log::warning("QuickBooks SalesReceipt webhook not set in config.");
            }
        } catch (\Throwable $e) {
            Log::error("QuickBooks SalesReceipt sync failed for payment {$payment->id}: " . $e->getMessage());
        }
    }

    return redirect()->route('property-invest.success')->with('success', 'Investment successful!');
}



public function wirepickSuccess(Request $request)
{
    $reference = $request->query('reference');
    $propertyId = $request->query('property_id');

    if (!$reference || !$propertyId) {
        return redirect()->route('properties.index')
               ->with('error', 'Missing payment reference.');
    }


    if (($payload->status ?? null) !== 'SUCCESSFUL') {
        return redirect()->route('properties.index')->with('error', 'Payment not completed.');
    }

    $partner = auth()->user()->partner;
    if (!$partner) {
        return redirect()->route('properties.index')
               ->with('error', 'Partner account not found.');
    }

    // Get payment record
    $payment = Payment::where('reference', $reference)->first();
    if (!$payment) {
        return redirect()->route('properties.index')
               ->with('error', 'Payment record not found.');
    }

    // Update payment → mark as paid
    $payment->update([
        'status'                 => 'paid',
        'quickbooks_customer_id' => $partner->quickbooks_customer_id,
    ]);

    // Load property & decrement shares
    $property = Property::find($propertyId);
    $shares = $payment->amount / $property->qbo_unit_price;

    if ($property && $shares > 0) {
        $before = $property->qbo_qty_on_hand;
        $property->decrement('qbo_qty_on_hand', $shares);

        Log::info("WIPEICK: Shares deducted: {$before} → {$property->qbo_qty_on_hand}");
    }

    // Push to QuickBooks
    try {
        $payload = [
            'quickbooks_customer_id' => $partner->quickbooks_customer_id,
            'amount'                 => $payment->amount,
            'currency'               => 'ZMW',
            'payment_id'             => $payment->id,
            'property'               => [
                'id'          => $property->id,
                'name'        => $property->property_name,
                'qbo_item_id' => $property->qbo_item_id,
                'unit_price'  => $property->qbo_unit_price,
                'qty_sold'    => $shares,
                'qty_remaining' => $property->qbo_qty_on_hand,
            ],
        ];

        $url = config('services.make.webhook_url_qb_sales_receipt');
        Http::acceptJson()->post($url, $payload);

        Log::info("WIPEICK-QB: Sales Receipt sent", $payload);

    } catch (\Throwable $e) {
        Log::error("Wirepick QB sync failed: " . $e->getMessage());
    }

    return redirect()->route('property-invest.success')
           ->with('success', 'Investment successful!');
}



    /**
     * Stripe cancel callback.
     */
    public function cancel()
    {
        return redirect()->route('properties.index')->with('error', 'Payment canceled.');
    }

    public function thankYou()
    {
        return view('cart.success');
    }
}
