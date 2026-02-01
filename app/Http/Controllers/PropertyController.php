<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PropertyController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
        // Optional: add permission middleware if needed
        // $this->middleware('permission:property.manage');
    }

    public function index()
    {
        // Show only properties owned by logged-in user
        $properties = Property::where('user_id', auth()->id())->latest()->paginate(12);
        return view('properties.index', compact('properties'));
    }

    public function create()
    {
        return view('properties.create');
    }


        public function propertyCreate()
    {
        return view('properties.create_form');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'property_name'    => ['required', 'string', 'max:190'],
            'slug'             => ['nullable', 'string', 'max:190', 'unique:properties,slug'],
            'address'          => ['nullable', 'string', 'max:255'],
            'property_contact' => ['nullable', 'string', 'max:100'],
            'property_email'   => ['nullable', 'email', 'max:190'],
            'lat'              => ['nullable', 'numeric', 'between:-90,90'],
            'lng'              => ['nullable', 'numeric', 'between:-180,180'],
            'radius_m'         => ['nullable', 'integer', 'min:50', 'max:2000'],
            'logo'             => ['nullable', 'image', 'max:2048'], // 2MB
        ]);

        // Auto-generate slug if not provided
        $data['slug'] = $data['slug'] ?? Str::slug($data['property_name']) . '-' . Str::random(5);

        // Assign to current user
        $data['user_id'] = $request->user()->id;

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $data['logo_path'] = $request->file('logo')->store('property_logos', 'public');
        }

        // Create Property
        $property = Property::create($data);

        return redirect()->route('properties.index', $property)
                         ->with('success', 'Property created successfully.');
    }


    public function syncFromQBO(Request $request)
{
    $request->validate([
        'name' => ['required', 'string'],
    ]);

    $response = Http::post(config('services.make.webhook_url_property'), [
        'name' => $request->name,
    ]);

    if ($response->failed()) {
        return back()->withErrors(['sync' => 'Failed to fetch property from QBO.']);
    }

    $payload = $response->json();

    if (!($payload['success'] ?? false)) {
        return back()->withErrors(['sync' => 'QBO did not return a match for that property.']);
    }

    // Find or create property
    $property = Property::firstOrNew(['slug' => Str::slug($payload['qbo_name'])]);
    $property->property_name   = $payload['qbo_name'];
    $property->total_shares    = $payload['qbo_qty_on_hand'];
    $property->bidding_price   = $payload['qbo_unit_price'] * $payload['qbo_qty_on_hand'];
    $property->qbo_item_id     = $payload['qbo_item_id'];
    $property->qbo_qty_on_hand = $payload['qbo_qty_on_hand'];
    $property->qbo_unit_price  = $payload['qbo_unit_price'];
    $property->save();

    /**
     * ✅ Record an activity log
     */
    // ActivityLogger::log(
    //     action: 'property.synced_from_qbo',
    //     subject: $property,
    //     properties: [
    //         'qbo_name' => $payload['qbo_name'],
    //         'qbo_item_id' => $payload['qbo_item_id'],
    //         'qty_on_hand' => $payload['qbo_qty_on_hand'],
    //         'unit_price' => $payload['qbo_unit_price'],
    //         'bidding_price' => $property->bidding_price,
    //     ],
    //     description: 'Property synced from QuickBooks Online via webhook.',
    // );

    return redirect()
        ->route('properties.show', $property->slug)
        ->with('success', 'Property synced from QuickBooks successfully!');
}

    // Dropzone-style upload handler
    public function upload(Request $request)
    {
        $request->validate([
            'file' => ['required', 'image', 'max:2048'],
        ]);

        $path = $request->file('file')->store('property_logos', 'public');

        return response()->json(['path' => $path], 200);
    }

    // public function show(Property $property)
    // {
    //     $this->authorizeOwner($property);
    //     return view('properties.show', compact('property'));
    // }


    public function show(Property $property)
{
    $user = Auth::user();

    $hasRole = fn ($r) => method_exists($user, 'hasRole') && $user->hasRole($r);

    if ($hasRole('investor')) {
        return view('properties.investor_show', compact('property'));
    }

            // detect mobile
    $agent = new Agent();

      if ($agent->isMobile()) {
    return view('properties.show_mobile', compact('property'));
    }

    // tenant / investor / others
    return view('properties.show', compact('property'));
}

    public function edit(Property $property)
    {
        $this->authorizeOwner($property);
        return view('properties.edit', compact('property'));
    }

    public function update(Request $request, Property $property)
    {
        $this->authorizeOwner($property);

        $data = $request->validate([
            'property_name'    => ['required', 'string', 'max:190'],
            'slug'             => ['required', 'string', 'max:190', 'unique:properties,slug,' . $property->id],
            'address'          => ['nullable', 'string', 'max:255'],
            'property_contact' => ['nullable', 'string', 'max:100'],
            'property_email'   => ['nullable', 'email', 'max:190'],
            'lat'              => ['nullable', 'numeric', 'between:-90,90'],
            'lng'              => ['nullable', 'numeric', 'between:-180,180'],
            'radius_m'         => ['nullable', 'integer', 'min:50', 'max:2000'],
            'logo'             => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('logo')) {
            $data['logo_path'] = $request->file('logo')->store('property_logos', 'public');
        }

        $property->update($data);

        return redirect()->route('properties.index', $property)
                         ->with('success', 'Property updated successfully.');
    }

    public function destroy(Property $property)
    {
        $this->authorizeOwner($property);
        $property->delete();

        return redirect()->route('properties.index')
                         ->with('success', 'Property deleted successfully.');
    }

    private function authorizeOwner(Property $property): void
    {
        if ($property->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
    }
}
