<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Property;

class AdminController extends Controller
{




public function index(Request $request)
{
    if (!Auth::check()) {
        return redirect()->route('login');
    }

    $user = Auth::user();

    // LANDLORD LOGIC (unchanged)
    if ($user->hasRole('landlord')) {

        $hasProperty = Property::where('user_id', $user->id)->exists();

        if (! $hasProperty) {
            return redirect()->route('properties.create_form');
        }

        return redirect()->route('dashboard.landlord');
    }

    // ✅ PENDING CLIENTS COUNT
    $pendingClientsCount = User::where('status', 'pending')->count();

    return view('dashboard.index', compact(
        'user',
        'pendingClientsCount'
    ));
}


public function landlordDashboard()
{
    $user = Auth::user();

    // Get landlord's primary property (latest or first)
    $property = Property::where('user_id', $user->id)->first();

    // Safety fallback (should not normally happen)
    if (! $property) {
        return redirect()->route('properties.create_form');
    }

    return view('dashboard.landlord', compact('property'));
}







    public function logout(Request $request)
    {
        Auth::logout(); // Logs out the user

        $request->session()->invalidate(); // Invalidates the session
        $request->session()->regenerateToken(); // Regenerates the CSRF token

        return redirect('/login'); // Redirect to the login page
    }

}
