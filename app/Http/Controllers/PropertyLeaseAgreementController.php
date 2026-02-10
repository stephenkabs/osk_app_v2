<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use App\Models\Unit;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use App\Mail\LeaseSignedPdfMail;

use App\Models\PropertyLeaseAgreement;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendLeaseForSignature;
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

public function signedThankYou(Property $property, PropertyLeaseAgreement $agreement)
{
    abort_unless($agreement->property_id === $property->id, 404);

    return view('properties.agreements.signed-thank-you', [
        'property'  => $property,
        'agreement' => $agreement,
        'tenant'    => $agreement->tenant,
    ]);
}


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



public function assignFromBoard(Request $request, Property $property)
{
    // 👇 DEBUG SAFETY (remove later)
    // logger($request->all());

    $data = $request->validate([
        'user_id' => [
            'required',
            Rule::exists('users', 'id')->where(
                fn ($q) => $q->where('property_id', $property->id)
            ),
        ],
        'unit_id' => [
            'required',
            Rule::exists('units', 'id')->where(
                fn ($q) => $q->where('property_id', $property->id)
            ),
        ],
        'start_date' => ['required', 'date'],
    ]);

    // ❌ Prevent duplicate active/pending lease
    if (
        PropertyLeaseAgreement::where('property_id', $property->id)
            ->where('user_id', $data['user_id'])
            ->whereIn('status', ['pending', 'active'])
            ->exists()
    ) {
        return response()->json([
            'error' => 'Tenant already has an active or pending lease.'
        ], 422);
    }

    // 🔒 TRANSACTION (VERY IMPORTANT)
    $lease = DB::transaction(function () use ($data, $property) {

        // Lock unit row
        $unit = Unit::where('id', $data['unit_id'])
            ->where('property_id', $property->id)
            ->lockForUpdate()
            ->first();

        if (!$unit) {
            abort(422, 'Unit not found.');
        }

        if ($unit->status !== 'available') {
            abort(422, 'This unit is no longer available.');
        }

        // Reserve unit
        $unit->update(['status' => 'reserved']);

        // Create lease
return PropertyLeaseAgreement::create([
    'property_id'     => $property->id,
    'user_id'         => $data['user_id'],
    'unit_id'         => $unit->id,
    'start_date'      => $data['start_date'],
    'rent_amount'     => $unit->rent_amount,        // ✅ REQUIRED
    'deposit_amount' => $unit->deposit_amount ?? 0, // ✅ SAFE
    'status'          => 'pending',
    'slug'            => (string) Str::uuid(),
]);

    });

//     return response()->json([
//         'success'  => true,
//         'lease_id' => $lease->id,
// 'sign_url' => route(
//     'property.agreements.public.create',
//     $property->slug
// ) . '?lease=' . $lease->slug,

//     ]);


return response()->json([
    'success'  => true,
    'lease_id' => $lease->id,

    // 🔗 Public signing link
    'sign_url' => route(
        'property.agreements.public.create',
        $property->slug
    ) . '?lease=' . $lease->slug,

    // 📧 Email endpoint (THIS FIXES YOUR ERROR)
    'send_email_url' => route(
        'property.leases.send-email',
        [$property->slug, $lease->id]
    ),
]);

}

public function assignBoard(Property $property)
{
    $tenants = User::where('property_id', $property->id)
        ->with([
            'leases' => function ($q) use ($property) {
                $q->where('property_id', $property->id)
                  ->whereIn('status', ['pending', 'active'])
                  ->with('unit');
            }
        ])
        ->orderBy('name')
        ->get();

    $units = Unit::where('property_id', $property->id)
        ->where('status', 'available')
        ->orderBy('code')
        ->get();

    return view(
        'properties.agreements.assign-board',
        compact('property', 'tenants', 'units')
    );
}






public function assignAndGenerateLink(
    Request $request,
    Property $property,
    User $user
) {
    // 🔒 Always return JSON (prevents HTML responses)
    $request->headers->set('Accept', 'application/json');

    // ✅ Validate input (unit must belong to property)
    $data = $request->validate([
        'unit_id' => [
            'required',
            Rule::exists('units', 'id')->where(
                fn ($q) => $q->where('property_id', $property->id)
            ),
        ],
        'start_date' => ['required', 'date'],
    ]);

    // ❌ Prevent duplicate active/pending lease
    if (
        PropertyLeaseAgreement::where('property_id', $property->id)
            ->where('user_id', $user->id)
            ->whereIn('status', ['pending', 'active'])
            ->exists()
    ) {
        return response()->json([
            'error' => 'Tenant already has an active or pending lease.'
        ], 422);
    }

    // 🔒 Transaction = no double booking
    $lease = DB::transaction(function () use ($data, $property, $user) {

        /** @var Unit $unit */
        $unit = Unit::where('id', $data['unit_id'])
            ->where('property_id', $property->id)
            ->lockForUpdate()
            ->firstOrFail();

        if ($unit->status !== 'available') {
            abort(422, 'Selected unit is no longer available.');
        }

        // Reserve unit
        $unit->update(['status' => 'reserved']);

        // Create lease
        return PropertyLeaseAgreement::create([
            'property_id' => $property->id,
            'user_id'     => $user->id,
            'unit_id'     => $unit->id,
            'start_date'  => $data['start_date'],
            'status'      => 'pending',
            'slug'        => (string) Str::uuid(),
        ]);
    });

    // ✅ Return SIGNING LINK (this is what your JS needs)
    return response()->json([
        'success'  => true,
        'lease_id' => $lease->id,
        'sign_url' => route(
            'property.agreements.public.create',
            $property->slug
        ) . '?user=' . $user->id,
    ]);
}



public function publicCreate(Request $request, Property $property)
{
    $lease = null;

    if ($request->filled('lease')) {
        $lease = PropertyLeaseAgreement::where('slug', $request->lease)
            ->where('property_id', $property->id)
            ->with('unit','tenant')
            ->firstOrFail();
    }

    $user = $lease?->tenant;

    $units = Unit::where('property_id', $property->id)
        ->where('status', 'available')
        ->orderBy('code')
        ->get();

    return view(
        'properties.agreements.public_create',
        compact('property','user','units','lease')
    );
}



public function sendLeaseEmail(Property $property, PropertyLeaseAgreement $lease)
{
    abort_unless($lease->property_id === $property->id, 404);

    if ($lease->status !== 'pending') {
        return response()->json([
            'error' => 'Lease already signed.'
        ], 422);
    }

    if (!$lease->tenant?->email) {
        return response()->json([
            'error' => 'Tenant has no email address.'
        ], 422);
    }

    $signUrl = route(
        'property.agreements.public.create',
        $property->slug
    ) . '?lease=' . $lease->slug;

dispatch(function () use ($property, $lease, $signUrl) {

    (new \App\Mail\SendLeaseForSignature($property, $lease, $signUrl))
        ->sendViaBrevo($lease->tenant->email);

});


    return response()->json([
        'success' => true,
        'message' => 'Lease sent successfully.'
    ]);
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



// public function publicStore(Request $request, Property $property)
// {
//     $data = $request->validate([
//         'name'           => ['required','string','max:190'],
//         'email'          => ['required','email'],
//         'unit_id'        => ['nullable','exists:units,id'],
//         'start_date'     => ['required','date'],
//         'end_date'       => ['nullable','date','after_or_equal:start_date'],
//         'rent_amount'    => ['required','numeric','min:0'],
//         'deposit_amount' => ['nullable','numeric','min:0'],
//         'notes'          => ['nullable','string','max:2000'],
//         'agree_terms'    => ['accepted'],
//         'signature_data' => ['required','string'],
//     ]);

//     // Reuse/create tenant
//     $user = User::firstOrCreate(
//         ['email' => $data['email']],
//         ['name' => $data['name'], 'property_id' => $property->id]
//     );

//     // Prepare agreement payload
//     $payload = [
//         'user_id'         => $user->id,
//         'property_id'     => $property->id,
//         'unit_id'         => $data['unit_id'] ?? null,
//         'start_date'      => $data['start_date'],
//         'end_date'        => $data['end_date'] ?? null,
//         'rent_amount'     => $data['rent_amount'],
//         'deposit_amount'  => $data['deposit_amount'] ?? null,
//         'notes'           => $data['notes'] ?? null,
//         'status'          => 'pending',
//         'slug'            => \Illuminate\Support\Str::slug('lease-'.\Illuminate\Support\Str::random(6)),
//             // ✅ FREEZE TERMS
//     'terms_snapshot' => $property->leaseTemplate->terms,
//         'signed_at'       => now(),
//     ];

//     // Save signature image (base64 -> storage)
//     $sig = $data['signature_data'];
//     if (str_starts_with($sig, 'data:image/png;base64,')) {
//         $sig = substr($sig, strpos($sig, ',') + 1);
//     }
//     $sigBinary = base64_decode($sig, true);
//     if ($sigBinary === false) {
//         return back()->withErrors(['signature_data' => 'Invalid signature data.'])->withInput();
//     }
//     $sigPath = 'agreements/signatures/'.uniqid('sig_').'.png';
//     Storage::disk('public')->put($sigPath, $sigBinary);
//     $payload['tenant_signature_path'] = $sigPath;

//     // 🔒 Transaction so we don't double-book units
//     $agreement = DB::transaction(function () use ($payload, $data, $property) {

//         // If a unit was selected, lock it and ensure it's available & same property
//         if (!empty($data['unit_id'])) {
//             /** @var \App\Models\Unit|null $unit */
//             $unit = Unit::where('id', $data['unit_id'])
//                 ->where('property_id', $property->id)
//                 ->lockForUpdate() // row lock until transaction ends
//                 ->first();

//             if (!$unit) {
//                 abort(422, 'Selected unit not found for this property.');
//             }
//             if ($unit->status !== 'available') {
//                 abort(422, 'Sorry, that unit has just been taken. Please choose another one.');
//             }

//             // Mark as occupied now
//             $unit->update(['status' => 'occupied']);
//         }

//         // Create agreement after unit is reserved
//         return \App\Models\PropertyLeaseAgreement::create($payload);
//     });

//     return redirect()
//         ->route('property.agreements.pdf', [$property->slug, $agreement->slug])
//         ->with('success', 'Lease application submitted successfully.');
// }


public function publicStore(Request $request, Property $property)
{
    // ===============================
    // 🔍 CASE 1: ADMIN-ASSIGNED LEASE
    // ===============================
    if ($request->filled('lease_id')) {

        $lease = PropertyLeaseAgreement::where('id', $request->lease_id)
            ->where('property_id', $property->id)
            ->where('status', 'pending')
            ->with('unit')
            ->firstOrFail();

        $data = $request->validate([
            'signature_data' => ['required','string'],
            'agree_terms'    => ['accepted'],
        ]);

        // Save signature image
        $sig = $data['signature_data'];
        if (str_starts_with($sig, 'data:image/png;base64,')) {
            $sig = substr($sig, strpos($sig, ',') + 1);
        }

        $sigBinary = base64_decode($sig, true);
        if ($sigBinary === false) {
            return back()->withErrors(['signature_data' => 'Invalid signature data.']);
        }

        $sigPath = 'agreements/signatures/'.uniqid('sig_').'.png';
        Storage::disk('public')->put($sigPath, $sigBinary);

        DB::transaction(function () use ($lease, $sigPath) {

            // Activate lease
            $lease->update([
                'tenant_signature_path' => $sigPath,
                'signed_at'             => now(),
                'status'                => 'active',
            ]);

            // Occupy unit
            if ($lease->unit) {
                $lease->unit->update(['status' => 'occupied']);
            }
        });
// 📧 Send signed lease PDF
dispatch(function () use ($property, $lease) {

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView(
        'properties.agreements.pdf',
        [
            'property'  => $property,
            'agreement' => $lease,
            'template'  => $property->leaseTemplate,
        ]
    )->setPaper('a4');

    \App\Services\BrevoMailService::send(
        $lease->tenant->email,
        'Your Signed Lease Agreement',
        view('emails.lease-signed', compact('property','lease'))->render(),
        [
            [
                'name'    => 'Lease-'.$lease->slug.'.pdf',
                'content' => base64_encode($pdf->output()),
            ]
        ]
    );

})->onQueue('emails');

// Redirect to thank-you page
// ✅ Simple redirect (like kyc.pending)
return redirect()->route('properties.thankyou');

    }

    // ===============================
    // 🧾 CASE 2: PUBLIC SELF-APPLY
    // ===============================
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

    // Save signature
    $sig = $data['signature_data'];
    if (str_starts_with($sig, 'data:image/png;base64,')) {
        $sig = substr($sig, strpos($sig, ',') + 1);
    }

    $sigBinary = base64_decode($sig, true);
    if ($sigBinary === false) {
        return back()->withErrors(['signature_data' => 'Invalid signature data.']);
    }

    $sigPath = 'agreements/signatures/'.uniqid('sig_').'.png';
    Storage::disk('public')->put($sigPath, $sigBinary);

    // Create lease
    $agreement = DB::transaction(function () use ($data, $property, $user, $sigPath) {

        if (!empty($data['unit_id'])) {
            $unit = Unit::where('id', $data['unit_id'])
                ->where('property_id', $property->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($unit->status !== 'available') {
                abort(422, 'Unit no longer available.');
            }

            $unit->update(['status' => 'occupied']);
        }

        return PropertyLeaseAgreement::create([
            'user_id'                => $user->id,
            'property_id'            => $property->id,
            'unit_id'                => $data['unit_id'] ?? null,
            'start_date'             => $data['start_date'],
            'end_date'               => $data['end_date'] ?? null,
            'rent_amount'            => $data['rent_amount'],
            'deposit_amount'         => $data['deposit_amount'] ?? null,
            'notes'                  => $data['notes'] ?? null,
            'tenant_signature_path'  => $sigPath,
            'status'                 => 'active',
            'signed_at'              => now(),
            'slug'                   => Str::uuid(),
            'terms_snapshot'         => $property->leaseTemplate->terms,
        ]);
    });

    return redirect()
        ->route('property.agreements.pdf', [$property->slug, $agreement->slug])
        ->with('success', 'Lease application submitted successfully.');
}


public function thankYou(Property $property, PropertyLeaseAgreement $agreement)
{
    return view('properties.thank-you', [
        'property'  => $property,
        'agreement' => $agreement,
        'tenant'    => $agreement->tenant,
    ]);
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
