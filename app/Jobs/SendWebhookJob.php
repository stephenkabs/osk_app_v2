<?php

namespace App\Jobs;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendWebhookJob implements ShouldQueue
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
    public function handle()
    {
        // If no webhook callback URL was provided, skip
        if (!$this->tx->callback_url) {
            Log::info("No callback URL for transaction {$this->tx->id}");
            return;
        }

        // Build payload for merchant
        $payload = [
            "reference"     => $this->tx->reference,
            "status"        => $this->tx->status,
            "amount"        => $this->tx->amount,
            "gateway"       => $this->tx->gateway,
            "transaction_id"=> $this->tx->id,
            "mno_fintxn_id" => $this->tx->mno_fintxn_id,
            "raw_response"  => $this->tx->raw_response,
        ];

        try {
            $response = Http::post($this->tx->callback_url, $payload);

            Log::info("Webhook sent for transaction {$this->tx->id}", [
                'callback_url' => $this->tx->callback_url,
                'response'     => $response->body()
            ]);
        } catch (\Exception $e) {
            Log::error("Webhook failed for transaction {$this->tx->id}", [
                'error' => $e->getMessage()
            ]);
        }
    }
}
