<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Merchant;
use Illuminate\Http\Request;

class MerchantApiKeyMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $apiKey = $request->header('x-awcloud-key');

        if (!$apiKey) {
            return response()->json(['error' => 'API key missing'], 401);
        }

        $merchant = Merchant::where('api_key', $apiKey)
                    ->where('status', 'active')
                    ->first();

        if (!$merchant) {
            return response()->json(['error' => 'Invalid API key'], 403);
        }

        // Attach merchant to the request
        $request->merge(['merchant' => $merchant]);

        return $next($request);
    }
}
