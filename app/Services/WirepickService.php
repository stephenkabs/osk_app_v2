<?php

namespace App\Services;

use App\Models\WirepickToken;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use phpseclib3\Crypt\PublicKeyLoader;
use phpseclib3\Crypt\RSA;

class WirepickService
{
    private function resolveGatewayBase($gw)
    {
        return match (strtoupper($gw)) {
            "AIRTEL" => "https://apigw.thehotpatch.com/uat/airtelmoneyopenapi",
            "MTN"    => "https://apigw.thehotpatch.com/uat/mtnopenapi",
            "ZAMTEL" => "https://apigw.thehotpatch.com/uat/zamtelkwachaopenapi",
            default  => throw new \Exception("Invalid gateway: $gw")
        };
    }

    public function sendRequest($txn)
    {
        Log::info("WIREPICK: Sending request", [
            "txn_id"    => $txn->id,
            "gateway"   => $txn->gateway,
            "reference" => $txn->reference
        ]);

        $token = WirepickToken::latest()->first()?->access_token;

        if (!$token) {
            throw new \Exception("Wirepick access token missing.");
        }

        $endpoint = $this->resolveGatewayBase($txn->gateway) . "/request";

        $payload = [
            "msisdn"      => $txn->msisdn,
            "amount"      => $txn->amount,
            "reference"   => $txn->reference,
            "narration"   => $txn->description ?? "Payment via Wirepick",
            "environment" => "UAT",
            "currency"    => "ZMW",
            "gateway"     => strtoupper($txn->gateway),
            "action"      => "collect",
        ];

        Log::info("WIREPICK PAYLOAD", $payload);

        $response = Http::withToken($token)
            ->withHeaders([
                "x-api-key" => config("wirepick.api_key"),
                "Content-Type" => "application/json"
            ])
            ->post($endpoint, $payload);

        Log::info("WIREPICK RAW", [
            "status" => $response->status(),
            "body" => $response->body()
        ]);

        return $response->json();
    }

    public function checkStatus($txn)
    {
        $token = WirepickToken::latest()->first()?->access_token;

        if (!$token) {
            throw new \Exception("Wirepick access token missing. Generate token first.");
        }

        $endpoint = $this->resolveGatewayBase($txn->gateway) . "/status";

        $response = Http::withToken($token)
            ->withHeaders([
                "x-api-key" => config("wirepick.api_key"),
                "Content-Type" => "application/json"
            ])
            ->post($endpoint, [
                "gw_id"          => $txn->gw_id,
                "reference"      => $txn->reference,
                "mno_request_id" => $txn->mno_request_id,
                "action"         => "collect_status",
                "environment"    => "UAT",
            ]);

        Log::info("WIREPICK RAW RESPONSE", $response->json() ?? []);

        return $response->json();
    }
}
