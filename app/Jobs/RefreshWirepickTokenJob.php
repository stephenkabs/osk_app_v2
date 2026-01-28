<?php

namespace App\Jobs;

use App\Models\WirepickToken;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RefreshWirepickTokenJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

public function handle()
{
    Log::info("QUEUE: RefreshWirepickTokenJob started");

    $response = Http::asForm()
        ->withHeaders([
            "x-api-key"    => config("wirepick.api_key"),
            "Content-Type" => "application/x-www-form-urlencoded",
        ])
        ->withBasicAuth(
            config("wirepick.client_id"),
            config("wirepick.client_secret")
        )
        ->post("https://apigw.thehotpatch.com/uat/oauth2/token", [
            "grant_type"    => "client_credentials",
            "client_id"     => config("wirepick.client_id"),
            "client_secret" => config("wirepick.client_secret"),
        ]);

    if (!$response->successful()) {
        Log::error("WIREPICK TOKEN REFRESH FAILED", $response->json());
        return;
    }

    $data = $response->json();

    WirepickToken::create([
        "access_token" => $data["access_token"],
        "expires_at"   => now()->addSeconds($data["expires_in"]),
    ]);

    Log::info("WIREPICK: Background token refreshed successfully.");
    Log::info("QUEUE: RefreshWirepickTokenJob completed");
}


}
