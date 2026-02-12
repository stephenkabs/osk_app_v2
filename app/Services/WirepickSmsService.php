<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WirepickSmsService
{
    public static function send($phone, $text)
    {
        return Http::withHeaders([
            'wpkKey'       => config('services.wirepick_sms.api_key'),
            'Content-Type' => 'application/json',
        ])->post(config('services.wirepick_sms.url'), [
            "phone"   => $phone,
            "text"    => $text,
            "from"    => config('services.wirepick_sms.from'),
            "clmsgid" => uniqid(),
            "flash"   => "N",
            "dlr"     => "Y"
        ]);
    }
}
