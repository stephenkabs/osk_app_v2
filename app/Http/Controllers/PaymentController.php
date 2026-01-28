<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Services\WirepickService;
use App\Jobs\PollPaymentStatusJob;

class PaymentController extends Controller
{
    public function store(Request $request, WirepickService $wirepick)
    {
        // Merchant from API key middleware
        $merchant = $request->merchant;

        // Validation
        $data = $request->validate([
            'msisdn'       => 'required|min:9|max:12',
            'amount'       => 'required|numeric|min:1',
            'gateway'      => 'required|in:AIRTEL,MTN,ZAMTEL,CARD',
            'reference'    => 'required|min:8|max:32|unique:transactions,reference',
            'description'  => 'required|string|max:64',
            'callback_url' => 'nullable|url',
        ]);

        // Save transaction
        $tx = Transaction::create([
            'merchant_id' => $merchant->id,
            'msisdn'      => $data['msisdn'],
            'amount'      => $data['amount'],
            'gateway'     => $data['gateway'],
            'reference'   => $data['reference'],
            'callback_url'=> $data['callback_url'],
            'raw_request' => $data
        ]);

        // Determine gateway type for WirepickService
        $gatewayType = match ($data['gateway']) {
            'AIRTEL' => 'airtel',
            'MTN'    => 'mtn',
            'ZAMTEL' => 'zamtel',
            'CARD'   => 'card',
        };

        // Build Wirepick payload
        $payload = [
            "msisdn"      => $data['msisdn'],
            "amount"      => $data['amount'],
            "reference"   => $data['reference'],
            "narration"   => $data['description'],
            "gateway"     => $data['gateway'],
            "environment" => "UAT", // UAT for testing
            "action"      => "collect",
        ];

        // Call your Wirepick service
        $response = $wirepick->pay($payload, $gatewayType);

        // Update transaction
        $tx->update([
            'wirepick_gw_id' => $response['gw_id'] ?? null,
            'mno_request_id' => $response['mno_request_id'] ?? null,
            'status'         => $response['status'] ?? 'PENDING',
            'raw_response'   => $response
        ]);

        // Queue background polling
        PollPaymentStatusJob::dispatch($tx)->delay(now()->addSeconds(10));

        return response()->json([
            'status'          => 'ACCEPTED',
            'message'         => 'Payment request submitted.',
            'transaction_id'  => $tx->id,
            'reference'       => $tx->reference,
            'wirepick_status' => $response['status'] ?? 'PENDING'
        ], 202);
    }
}
