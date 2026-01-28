<?php

namespace App\Jobs;

use App\Models\Transaction;
use App\Services\WirepickService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PollPaymentStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Transaction $tx;

    /**
     * Create a new job instance.
     */
    public function __construct(Transaction $tx)
    {
        $this->tx = $tx;
    }

    /**
     * Execute the job.
     */
    public function handle(WirepickService $wirepick)
    {
        // If already final, stop polling
        if (in_array($this->tx->status, ['SUCCESSFUL', 'FAILED'])) {
            return;
        }

        $token = $wirepick->getToken();
        $endpoint = $wirepick->getStatusEndpoint($this->tx);

        $response = Http::withHeaders([
            'Authorization' => "Bearer $token",
            'x-api-key'     => env("WIREPICK_API_KEY")
        ])->post($endpoint, [
            "gw_id"          => $this->tx->wirepick_gw_id,
            "reference"      => $this->tx->reference,
            "mno_request_id" => $this->tx->mno_request_id,
            "action"         => "collect_status",
            "environment"    => "PROD"
        ]);

        $data = $response->json();

        // Update transaction
        $this->tx->update([
            'status'        => $data['status'] ?? $this->tx->status,
            'mno_fintxn_id' => $data['mno_fintxn_id'] ?? null,
            'raw_response'  => $data
        ]);

        // If final state reached → send webhook
        if (in_array($this->tx->status, ['SUCCESSFUL', 'FAILED'])) {
            dispatch(new SendWebhookJob($this->tx));
            return;
        }

        // Otherwise → poll again after 10 seconds
        self::dispatch($this->tx)->delay(now()->addSeconds(10));
    }
}
