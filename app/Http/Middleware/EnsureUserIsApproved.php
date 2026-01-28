<?php

// app/Http/Middleware/EnsureUserIsApproved.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsApproved
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (Auth::user()->status !== 'active') {
            Auth::logout();

            return redirect()
                ->route('login')
                ->withErrors([
                    'email' => 'Your account is pending approval. Please wait for admin confirmation.'
                ]);
        }

        return $next($request);
    }
}
