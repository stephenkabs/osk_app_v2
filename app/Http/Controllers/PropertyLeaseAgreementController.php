<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use App\Models\Unit;
use App\Models\PropertyLeaseAgreement;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PropertyLeaseAgreementController extends Controller
{
    public function index(Property $property)
    {
        $agreements = PropertyLeaseAgreement::with(['tenant','unit'])
            ->where('property_id', $property->id)
            ->latest()
            ->paginate(20);

        return view('properties.agreements.index', compact('property','agreements'));
    }

    public function create(Property $property)
    {
        $tenants = User::where('property_id', $property->id)->orderBy('name')->get();
        $units   = Unit::where('property_id', $property->id)->orderBy('code')->get();

        return view('properties.agreements.create', compact('property','tenants','units'));
    }

// PropertyLeaseAgreementController.php



public function store(Request $request, Property $property)
{
    $data = $request->validate([
        'user_id'        => ['required','exists:users,id'],
        'unit_id'        => ['nullable','exists:units,id'],
        'start_date'     => ['required','date'],
        'end_date'       => ['nullable','date','after_or_equal:start_date'],
        'payment_day'    => ['nullable','integer','min:1','max:31'],
        'rent_amount'    => ['nullable','numeric','min:0'],
        'deposit_amount' => ['nullable','numeric','min:0'],
        'utilities_cap'  => ['nullable','numeric','min:0'],
        'bank_name'      => ['nullable','string','max:190'],
        'account_name'   => ['nullable','string','max:190'],
        'account_number' => ['nullable','string','max:190'],
        'landlord_name'  => ['nullable','string','max:190'],
        'landlord_email' => ['nullable','email','max:190'],
        'tenant_email'   => ['nullable','email','max:190'],
        'tenant_id_no'   => ['nullable','string','max:190'],
        'reference'      => ['nullable','string','max:190'],
        'lease_number'   => ['nullable','string','max:190'],
        'status'         => ['nullable','in:pending,active,ended'],
        'signed_at'      => ['nullable','date'],
        'notes'          => ['nullable','string'],
    ]);

    // Defaults
    $data['property_id'] = $property->id;
    $data['status']      = $data['status'] ?? 'active';
    $data['payment_day'] = $data['payment_day'] ?? 1;

    // Auto-fill rent/deposit from unit if not provided
    if (!empty($data['unit_id'])) {
        $unit = Unit::where('property_id', $property->id)
            ->where('id', $data['unit_id'])
            ->first();

        if ($unit) {
            $data['rent_amount']    = $data['rent_amount']    ?? $unit->rent_amount;
            $data['deposit_amount'] = $data['deposit_amount'] ?? $unit->deposit_amount;
        }
    }

    // Ensure we have a rent amount by now
    if (!isset($data['rent_amount'])) {
        return back()
            ->withErrors(['rent_amount' => 'Please provide the rent amount or select a unit that has rent set.'])
            ->withInput();
    }

    // Safe slug build (no undefined index)
    $base = ($data['lease_number'] ?? 'lease-') . Str::random(6);
    $data['slug'] = Str::slug($base);

    $agreement = PropertyLeaseAgreement::create($data);

    return redirect()
        ->route('property.agreements.show', [$property->slug, $agreement->slug])
        ->with('success', 'Lease agreement created.');
}


// PropertyLeaseAgreementController@publicCreate
public function publicCreate(Request $request, Property $property)
{
    $userId = $request->query('user');        // <-- read from query
    $user   = $userId ? \App\Models\User::find($userId) : null;

    if (!$user) {
        // Optional: send them back to the public tenant form
        return redirect()
            ->route('property.users.public.create', $property->slug)
            ->with('error', 'Tenant not found. Please register first.');
    }

    $units = \App\Models\Unit::where('property_id', $property->id)
        ->where('status', 'available')
        ->orderBy('code')
        ->get();

    return view('properties.agreements.public_create', compact('property','user','units'));
}

private function imageToDataUri(?string $storagePath): ?string
{
    if (!$storagePath) return null;

    try {
        // read from storage/app/public/...
        $full = storage_path('app/public/'.$storagePath);
        if (!file_exists($full)) return null;

        $bin  = file_get_contents($full);
        if ($bin === false) return null;

        // crude mime detect by extension (good enough for profile uploads)
        $ext  = strtolower(pathinfo($full, PATHINFO_EXTENSION));
        $mime = $ext === 'jpg' || $ext === 'jpeg' ? 'image/jpeg'
              : ($ext === 'gif' ? 'image/gif' : 'image/png');

        return 'data:'.$mime.';base64,'.base64_encode($bin);
    } catch (\Throwable $e) {
        return null;
    }
}

public function pdf(Property $property, PropertyLeaseAgreement $agreement)
{
    abort_unless($agreement->property_id === $property->id, 404);

    $agreement->load(['tenant','unit','property']);

    $template = $property->leaseTemplate;

    if (!$template) {
        abort(500, 'Lease template not found for this property.');
    }

    $logoData = $this->imageToDataUri($property->logo_path ?? null);
    $sigData  = $this->imageToDataUri($agreement->tenant_signature_path ?? null);
    $tenantPhotoData = $this->imageToDataUri($agreement->tenant->profile_image ?? null);

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView(
        'properties.agreements.pdf',
        compact(
            'property',
            'agreement',
            'template',
            'logoData',
            'sigData',
            'tenantPhotoData'
        )
    )->setPaper('a4');

    return $pdf->stream('Lease-' . ($agreement->lease_number ?: $agreement->slug) . '.pdf');
}


public function download(Property $property, PropertyLeaseAgreement $agreement)
{
    // Ensure agreement belongs to property
    abort_unless($agreement->property_id === $property->id, 404);

    $agreement->load(['tenant','unit','property']);

    // 🔑 LOAD TEMPLATE (THIS WAS MISSING)
    $template = $property->leaseTemplate;

    if (!$template) {
        abort(500, 'Lease template not found for this property.');
    }

    $logoData = $this->imageToDataUri($property->logo_path ?? null);
    $sigData  = $this->imageToDataUri($agreement->tenant_signature_path ?? null);
    $tenantPhotoData = $this->imageToDataUri($agreement->tenant->profile_image ?? null);

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView(
        'properties.agreements.pdf',
        [
            'property'        => $property,
            'agreement'       => $agreement,
            'template'        => $template, // ✅ FIX
            'logoData'        => $logoData,
            'sigData'         => $sigData,
            'tenantPhotoData' => $tenantPhotoData,
        ]
    )->setPaper('a4');

    $filename = 'Lease-' . ($agreement->lease_number ?: $agreement->slug);

    return $pdf->download($filename . '.pdf');
}



public function publicStore(Request $request, Property $property)
{
    $data = $request->validate([
        'name'           => ['required','string','max:190'],
        'email'          => ['required','email'],
        'unit_id'        => ['nullable','exists:units,id'],
        'start_date'     => ['required','date'],
        'end_date'       => ['nullable','date','after_or_equal:start_date'],
        'rent_amount'    => ['required','numeric','min:0'],
        'deposit_amount' => ['nullable','numeric','min:0'],
        'notes'          => ['nullable','string','max:2000'],
        'agree_terms'    => ['accepted'],
        'signature_data' => ['required','string'],
    ]);

    // Reuse/create tenant
    $user = User::firstOrCreate(
        ['email' => $data['email']],
        ['name' => $data['name'], 'property_id' => $property->id]
    );

    // Prepare agreement payload
    $payload = [
        'user_id'         => $user->id,
        'property_id'     => $property->id,
        'unit_id'         => $data['unit_id'] ?? null,
        'start_date'      => $data['start_date'],
        'end_date'        => $data['end_date'] ?? null,
        'rent_amount'     => $data['rent_amount'],
        'deposit_amount'  => $data['deposit_amount'] ?? null,
        'notes'           => $data['notes'] ?? null,
        'status'          => 'pending',
        'slug'            => \Illuminate\Support\Str::slug('lease-'.\Illuminate\Support\Str::random(6)),
            // ✅ FREEZE TERMS
    'terms_snapshot' => $property->leaseTemplate->terms,
        'signed_at'       => now(),
    ];

    // Save signature image (base64 -> storage)
    $sig = $data['signature_data'];
    if (str_starts_with($sig, 'data:image/png;base64,')) {
        $sig = substr($sig, strpos($sig, ',') + 1);
    }
    $sigBinary = base64_decode($sig, true);
    if ($sigBinary === false) {
        return back()->withErrors(['signature_data' => 'Invalid signature data.'])->withInput();
    }
    $sigPath = 'agreements/signatures/'.uniqid('sig_').'.png';
    Storage::disk('public')->put($sigPath, $sigBinary);
    $payload['tenant_signature_path'] = $sigPath;

    // 🔒 Transaction so we don't double-book units
    $agreement = DB::transaction(function () use ($payload, $data, $property) {

        // If a unit was selected, lock it and ensure it's available & same property
        if (!empty($data['unit_id'])) {
            /** @var \App\Models\Unit|null $unit */
            $unit = Unit::where('id', $data['unit_id'])
                ->where('property_id', $property->id)
                ->lockForUpdate() // row lock until transaction ends
                ->first();

            if (!$unit) {
                abort(422, 'Selected unit not found for this property.');
            }
            if ($unit->status !== 'available') {
                abort(422, 'Sorry, that unit has just been taken. Please choose another one.');
            }

            // Mark as occupied now
            $unit->update(['status' => 'occupied']);
        }

        // Create agreement after unit is reserved
        return \App\Models\PropertyLeaseAgreement::create($payload);
    });

    return redirect()
        ->route('property.agreements.pdf', [$property->slug, $agreement->slug])
        ->with('success', 'Lease application submitted successfully.');
}


    public function show(Property $property, PropertyLeaseAgreement $agreement)
    {
        $agreement->load(['tenant','unit','property']);
        return view('properties.agreements.show', compact('property','agreement'));
    }

    public function edit(Property $property, PropertyLeaseAgreement $agreement)
    {
        $tenants = User::where('property_id', $property->id)->orderBy('name')->get();
        $units   = Unit::where('property_id', $property->id)->orderBy('code')->get();
        return view('properties.agreements.edit', compact('property','agreement','tenants','units'));
    }

public function update(Request $request, Property $property, \App\Models\PropertyLeaseAgreement $agreement)
{
    $data = $request->validate([
        'user_id'        => ['required','exists:users,id'],
        'unit_id'        => ['nullable','exists:units,id'],   // 👈 here
        'start_date'     => ['required','date'],
        'end_date'       => ['nullable','date','after_or_equal:start_date'],
        'payment_day'    => ['required','integer','min:1','max:31'],
        'rent_amount'    => ['required','numeric','min:0'],
        'deposit_amount' => ['nullable','numeric','min:0'],
        'utilities_cap'  => ['nullable','numeric','min:0'],
        'bank_name'      => ['nullable','string','max:190'],
        'account_name'   => ['nullable','string','max:190'],
        'account_number' => ['nullable','string','max:190'],
        'landlord_name'  => ['nullable','string','max:190'],
        'landlord_email' => ['nullable','email','max:190'],
        'tenant_email'   => ['nullable','email','max:190'],
        'tenant_id_no'   => ['nullable','string','max:190'],
        'reference'      => ['nullable','string','max:190'],
        'lease_number'   => ['nullable','string','max:190'],
        'status'         => ['required','in:pending,active,ended'],
        'signed_at'      => ['nullable','date'],
        'notes'          => ['nullable','string'],
    ]);

    if (empty($agreement->lease_number) && !empty($data['lease_number'])) {
        $data['slug'] = \Illuminate\Support\Str::slug($data['lease_number'].'-'.\Illuminate\Support\Str::random(4));
    }

    $agreement->update($data);

    return redirect()
        ->route('property.agreements.show', [$property->slug, $agreement->slug])
        ->with('success','Lease agreement updated.');
}
    public function destroy(Property $property, PropertyLeaseAgreement $agreement)
    {
        $agreement->delete();
        return redirect()->route('property.agreements.index', $property->slug)
            ->with('success','Lease agreement deleted.');
    }


    public function updateStatus(Request $request, Property $property, PropertyLeaseAgreement $agreement)
{
    // 🔒 Ensure lease belongs to property
    abort_unless($agreement->property_id === $property->id, 404);

    $data = $request->validate([
        'status' => 'required|in:pending,active,ended',
    ]);

    // 🧠 Business rules (optional but recommended)
    if ($agreement->status === 'ended') {
        return back()->withErrors([
            'status' => 'An ended lease cannot be changed.'
        ]);
    }

    // If activating → ensure signed_at exists
    if ($data['status'] === 'active' && is_null($agreement->signed_at)) {
        return back()->withErrors([
            'status' => 'Lease must be signed before activation.'
        ]);
    }

    // ✅ Update status
    $agreement->update([
        'status' => $data['status'],
    ]);

    return back()->with('success', 'Lease status updated successfully.');
}



}
