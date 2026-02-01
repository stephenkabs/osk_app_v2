<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoginAttempt;
use Illuminate\Support\Facades\DB;

class LoginAttemptController extends Controller
{


public function index()
{
    // ===============================
    // TABLE DATA (grouped attempts)
    // ===============================
    $attempts = LoginAttempt::select(
            'email',
            'ip_address',
            DB::raw('COUNT(*) as attempts'),
            DB::raw('MAX(created_at) as last_attempt')
        )
        ->groupBy('email', 'ip_address')
        ->orderByDesc('attempts')
        ->get();

    // ===============================
    // GRAPH DATA (last 7 days)
    // ===============================
    $chartData = LoginAttempt::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as total')
        )
        ->where('created_at', '>=', now()->subDays(7))
        ->groupBy(DB::raw('DATE(created_at)'))
        ->orderBy('date')
        ->get();

    return view('admin.security.login-attempts', [
        'title'      => 'Login Security Monitor',
        'attempts'   => $attempts,
        'chartData'  => $chartData,
    ]);
}

}
