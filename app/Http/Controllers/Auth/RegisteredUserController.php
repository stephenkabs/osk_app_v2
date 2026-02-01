<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class RegisteredUserController extends Controller
{
    /**
     * Show registration form
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle Registration
     */
public function store(Request $request): RedirectResponse
{
    // 1) Base validation
    $validated = $request->validate([
        'name'     => ['required', 'string', 'max:255'],
        'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
        'type'     => ['required', 'in:tenant,investor'],
    ]);

    // 2) Tenant KYC (only when type=tenant)
    if ($request->input('type') === 'tenant') {
        $request->validate([
            'phone'            => ['required','string','max:50'],
            'nationality'      => ['required','string','max:120'],
            'id_type'          => ['required','in:NRC,Passport,DL'],
            'id_number'        => ['required','string','max:190','unique:users,id_number'],
            // 'id_expiry'        => ['required','date'],
            'address'          => ['required','string','max:1000'],
            'city'             => ['required','string','max:120'],
            'country'          => ['required','string','max:120'],
            // 'proof_of_address' => ['required','file','mimes:jpg,jpeg,png,pdf','max:2048'],
        ]);
    }

    // 3) Unique slug from name (avoid Stringable by casting explicitly)
    $base = Str::slug((string) $request->input('name'));
    $slug = $base ?: Str::random(8);
    $i = 1;
    while (\App\Models\User::where('slug', $slug)->exists()) {
        $slug = $base.'-'.$i++;
    }

    // 4) Create user (cast inputs to plain strings to avoid Stringable issues)
    $user = \App\Models\User::create([
        'name'     => (string) $request->input('name'),
        'email'    => strtolower((string) $request->input('email')),
        'type'     => (string) $request->input('type'),
        'password' => Hash::make((string) $request->input('password')),
        'slug'     => $slug,
    ]);

    // 5) If tenant, persist KYC fields (assuming columns exist on users table)
    if ($user->type === 'tenant') {
        $poaPath = null;
        if ($request->hasFile('proof_of_address')) {
            $poaPath = $request->file('proof_of_address')->store('users/proof_of_address', 'public');
        }

        $user->fill([
            'phone'            => (string) $request->input('phone'),
            'nationality'      => (string) $request->input('nationality'),
            'id_type'          => (string) $request->input('id_type'),
            'id_number'        => (string) $request->input('id_number'),
            'id_expiry'        => $request->date('id_expiry'),
            'address'          => (string) $request->input('address'),
            'city'             => (string) $request->input('city'),
            'country'          => (string) $request->input('country'),
            'proof_of_address' => $poaPath,
        ])->save();
    }

    // 6) Role assignment (spatie/permission) — ensure role exists and assign by plain string
    $guard = config('auth.defaults.guard', 'web');
    Role::findOrCreate($user->type, $guard);
    if (method_exists($user, 'syncRoles')) {
        $user->syncRoles([$user->type]); // 'tenant' or 'investor'
    }

    // 7) Fire events / welcome mail (optional)
    // event(new Registered($user));
    // try {
    //     Mail::to($user->email)->send(new WelcomeMail($user));
    // } catch (\Throwable $e) {
    //     report($e); // optional
    // }

    // 8) Login & redirect
    Auth::login($user);

    return $user->type === 'investor'
        ? redirect()->route('partners.investor_kyc')->with('success', 'Welcome! Please complete your Investor KYC.')
        : redirect()->route('dashboard.index')->with('success', 'Welcome! Please complete your Tenant KYC.');
}



    private function generateUniqueSlug($name)
{
    $slug = Str::slug($name);
    $original = $slug;

    $count = 1;
    while (User::where('slug', $slug)->exists()) {
        $slug = $original . '-' . $count;
        $count++;
    }

    return $slug;
}

}
