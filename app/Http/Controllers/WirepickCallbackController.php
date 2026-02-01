<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;   // ✅ For sending requests to Make
use App\Models\PropertyPayment;

class WirepickCallbackController extends Controller
{
    public function handle(Request $request)
    {
        Log::info('WIREPICK CALLBACK RECEIVED', $request->all());

        /*
         Expected Wirepick payload example:
         {
           "reference": "RENT-12",
           "status": "SUCCESS",
           "transactionId": "WP123456",
           "amount": "3000.00"
         }
        */

        $reference = $request->input('reference'); // RENT-{payment_id}

        if (! $reference || ! str_starts_with($reference, 'RENT-')) {
            return response()->json(['error' => 'Invalid reference'], 400);
        }

        $paymentId = (int) str_replace('RENT-', '', $reference);

        $payment = PropertyPayment::find($paymentId);

        if (! $payment) {
            return response()->json(['error' => 'Payment not found'], 404);
        }

        // Normalize status
        $status = strtoupper($request->input('status'));

        if ($status === 'SUCCESS') {
            $payment->update([
                'gateway_status' => 'success',
                'status'         => 'paid',
            ]);
        } elseif ($status === 'FAILED') {
            $payment->update([
                'gateway_status' => 'failed',
            ]);
        }

        return response()->json(['ok' => true]);
    }
}
