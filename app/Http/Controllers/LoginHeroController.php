<?php

namespace App\Http\Controllers;

use App\Models\LoginHero;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LoginHeroController extends Controller
{
    /**
     * Store or update login hero section
     */
public function store(Request $request)
{
    $request->validate([
        'title'   => 'required|string|max:255',
        'text'    => 'nullable|string',
        'tagline' => 'nullable|string|max:255',
        'image'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
    ]);

    // Get active hero (do NOT auto-insert)
    $hero = LoginHero::where('is_active', true)->first();

    // If none exists, create properly with required fields
    if (!$hero) {
        $hero = LoginHero::create([
            'title'     => $request->title,
            'text'      => $request->text,
            'tagline'   => $request->tagline,
            'is_active' => true,
        ]);
    }

    // Upload image to DigitalOcean Spaces
    if ($request->hasFile('image')) {

        if ($hero->image) {
            Storage::disk('spaces')->delete($hero->image);
        }

        $path = Storage::disk('spaces')->putFile(
            'login-hero',
            $request->file('image'),
            'public'
        );

        $hero->image = $path;
    }

    // Update remaining fields
    $hero->update([
        'title'   => $request->title,
        'text'    => $request->text,
        'tagline' => $request->tagline,
    ]);

    return back()->with('success', 'Login screen updated successfully.');
}



    public function edit()
{
    $hero = \App\Models\LoginHero::where('is_active', true)->first();

    return view('login-hero.edit', compact('hero'));
}

}
