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
    // Validate all your new fields
    $request->validate([
        'name'              => ['required', 'string', 'max:255'],
        'email'             => ['required', 'email', 'max:255', 'unique:users,email'],
        'whatsapp_line'     => ['nullable', 'string', 'max:255'],
        'whatsapp_phone'    => ['nullable', 'string', 'max:255', 'unique:users,whatsapp_phone'],
        'address'           => ['nullable', 'string', 'max:255'],
        'occupation'        => ['nullable', 'string', 'max:255'],
        'nrc'               => ['nullable', 'string', 'max:255'],
        'country'           => ['nullable', 'string', 'max:255'],
        'type'              => ['nullable', 'string', 'max:255'],
        'special_code'      => ['nullable', 'string', 'max:10'],
        'profile_image'     => ['nullable'], // camera base64
        'password'          => ['required', 'confirmed', Rules\Password::defaults()],
    ]);

    // CREATE USER
    $user = new User();
    $user->name           = $request->name;
    $user->email          = $request->email;

    // 🚀 AUTO GENERATE UNIQUE SLUG
    $user->slug = $this->generateUniqueSlug($request->name);

    $user->whatsapp_line  = $request->whatsapp_line;
    $user->whatsapp_phone = $request->whatsapp_phone;
    $user->address        = $request->address;
    $user->occupation     = $request->occupation;
    $user->nrc            = $request->nrc;
    $user->country        = $request->country;
    $user->type           = $request->type;
    $user->special_code   = $request->special_code;
    $user->password       = Hash::make($request->password);

    // HANDLE SELFIE (BASE64 CAMERA IMAGE)
    if ($request->profile_image) {

        $base64 = preg_replace('/^data:image\/\w+;base64,/', '', $request->profile_image);
        $image  = base64_decode($base64);

        $fileName = 'profile_'.time().'.jpg';

        Storage::disk('public')->put('profiles/'.$fileName, $image);

        $user->profile_image = 'profiles/'.$fileName;
    }

    // Assign roles
    $user->syncRoles($request->roles);
    $user->save();

    event(new Registered($user));
    Auth::login($user);

    return redirect(RouteServiceProvider::HOME);
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
