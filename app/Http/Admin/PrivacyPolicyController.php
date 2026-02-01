<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PrivacyPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrivacyPolicyController extends Controller
{
    /**
     * Show privacy policy (public)
     */
    public function show()
    {
        $policy = PrivacyPolicy::where('is_active', true)->latest()->first();

        return view('privacy.show', compact('policy'));
    }


        public function show_web()
    {
        $policy = PrivacyPolicy::where('is_active', true)->latest()->first();

        return view('privacy.show_web', compact('policy'));
    }


    /**
     * Admin list
     */
    public function index()
    {
        $policies = PrivacyPolicy::latest()->paginate(10);

        return view('admin.privacy.index', compact('policies'));
    }

    /**
     * Create form
     */
    public function create()
    {
        return view('admin.privacy.create');
    }

    /**
     * Store
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
            'is_active' => 'boolean',
        ]);

        // Deactivate old active policy
        if ($request->boolean('is_active')) {
            PrivacyPolicy::where('is_active', true)->update(['is_active' => false]);
        }

        PrivacyPolicy::create([
            ...$data,
            'user_id' => Auth::id(),
        ]);

        return redirect()
            ->route('admin.privacy.index')
            ->with('success', 'Privacy policy created successfully.');
    }

    /**
     * Edit
     */
    public function edit(PrivacyPolicy $privacyPolicy)
    {
        return view('admin.privacy.edit', compact('privacyPolicy'));
    }

    /**
     * Update
     */
    public function update(Request $request, PrivacyPolicy $privacyPolicy)
    {
        $data = $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
            'is_active' => 'boolean',
        ]);

        if ($request->boolean('is_active')) {
            PrivacyPolicy::where('is_active', true)
                ->where('id', '!=', $privacyPolicy->id)
                ->update(['is_active' => false]);
        }

        $privacyPolicy->update($data);

        return redirect()
            ->route('admin.privacy.index')
            ->with('success', 'Privacy policy updated successfully.');
    }

    /**
     * Delete
     */
    public function destroy(PrivacyPolicy $privacyPolicy)
    {
        $privacyPolicy->delete();

        return redirect()
            ->route('admin.privacy.index')
            ->with('success', 'Privacy policy deleted.');
    }
}
