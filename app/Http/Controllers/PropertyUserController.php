<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Jenssegers\Agent\Agent;
use App\Models\PropertyPayment;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Storage;

class PropertyUserController extends Controller
{
    /**
     * List users for a property (with time-range + search filters)
     */
    public function index(Request $request, Property $property)
    {
        $range = $request->get('range', 'all');   // week|month|year|all
        $q     = trim($request->get('q', ''));    // search text
        $now   = Carbon::now();

        // Base scoped to this property
        $base = $property->users()
            ->select('id','name','email','slug','profile_image','created_at','whatsapp_phone');

        if (Schema::hasColumn('users', 'status')) $base->addSelect('status');
        if (Schema::hasColumn('users', 'type'))   $base->addSelect('type');

        // Apply time range
        $filtered = clone $base;
        switch ($range) {
            case 'week':
                $filtered->whereBetween('created_at', [$now->copy()->startOfWeek(), $now->copy()->endOfWeek()]);
                break;
            case 'month':
                $filtered->whereYear('created_at', $now->year)
                         ->whereMonth('created_at', $now->month);
                break;
            case 'year':
                $filtered->whereYear('created_at', $now->year);
                break;
            case 'all':
            default:
                // no extra filter
                break;
        }

        // Search
        if ($q !== '') {
            $filtered->where(function($qq) use ($q) {
                $qq->where('name', 'like', "%{$q}%")
                   ->orWhere('email', 'like', "%{$q}%");
            });
        }

        // Paginated list
        $users = $filtered->latest()->paginate(24)->appends($request->query());

        // Totals (same scope)
        $totalMembers  = (clone $filtered)->count();
        $activeMembers = Schema::hasColumn('users', 'status')
            ? (clone $filtered)->where('status', 1)->count()
            : null;
        $newToday      = (clone $filtered)->whereDate('created_at', Carbon::today())->count();

        return view('properties.users.index', compact(
            'property', 'users', 'range', 'q', 'totalMembers', 'activeMembers', 'newToday'
        ));
    }

    /**
     * Public signup form (property selection)
     */
    public function showPublicForm()
    {
        $properties = Property::orderBy('property_name')->get();
        return view('auth.public_register', compact('properties'));
    }

    /**
     * Public signup store
     */
public function publicStore(Request $request, Property $property)
{
    $data = $request->validate([
        'name'              => 'required|string|max:190',
        'email'             => 'required|email|unique:users,email',
        'password'          => 'required|string|min:6|confirmed',
        'type'              => 'nullable|array',
        'type.*'            => 'string',
        // 'whatsapp_line'     => 'nullable|string|max:190',
        'whatsapp_phone'    => 'required|string|max:190|unique:users,whatsapp_phone',
        'address'           => 'nullable|string|max:255',
        // 'occupation'        => 'nullable|string|max:190',
        'profile_image'     => 'nullable|image|max:2048',
        'camera_image'      => 'nullable|image|max:2048',
        'camera_image_data' => 'nullable|string',
    ]);

    $data['type']          = isset($data['type']) ? implode(',', $data['type']) : null;
    $data['profile_image'] = $this->handleImageUpload($request);
    $data['password']      = \Illuminate\Support\Facades\Hash::make($data['password']);
    $data['property_id']   = $property->id;
    $data['slug']          = \Illuminate\Support\Str::slug($data['name']).'-'.\Illuminate\Support\Str::random(5);

    // Create tenant account
    $user = \App\Models\User::create($data);
    $user->assignRole('tenant');

    // ✅ Redirect directly to public lease agreement creation
// ✅ Redirect directly to public lease agreement creation
return redirect()->route('kyc.pending' );

}


    public function publicCreate(Property $property)
{
    // Minimal public form (no admin chrome)
    return view('properties.users.public_create', compact('property'));
}

public function exportCsv(Property $property)
{
    $fileName = 'tenants_import_ready_' . now()->format('Ymd_His') . '.csv';

    return response()->stream(function () use ($property) {
        $file = fopen('php://output', 'w');

        // CSV HEADER
        fputcsv($file, [
            'name',
            'email',
            'whatsapp_phone',
            'address',
            'property_id', // informational only
            'password',
            'status',
        ]);

        $property->users()->each(function ($user) use ($file, $property) {
            fputcsv($file, [
                $user->name,
                $user->email,
                $user->whatsapp_phone,
                $user->address,
                $property->id, // shown but NOT trusted on import
                '',
                $user->status ?? 'active',
            ]);
        });

        fclose($file);
    }, 200, [
        'Content-Type'        => 'text/csv',
        'Content-Disposition' => "attachment; filename={$fileName}",
    ]);
}

public function importCsv(Request $request, Property $property)
{
    $request->validate([
        'csv' => 'required|file|mimes:csv,txt',
    ]);

    $handle = fopen($request->file('csv')->getRealPath(), 'r');

    // Read header
    $header = fgetcsv($handle);

    $expected = [
        'name',
        'email',
        'whatsapp_phone',
        'address',
        'property_id',
        'password',
        'status',
    ];

    if ($header !== $expected) {
        return back()->withErrors([
            'csv' => 'Invalid CSV format. Please use the exported template.',
        ]);
    }

    while (($row = fgetcsv($handle)) !== false) {
        [
            $name,
            $email,
            $phone,
            $address,
            $_propertyId, // ❌ IGNORED
            $password,
            $status
        ] = $row;

        $status = in_array($status, ['active','pending','ended'])
            ? $status
            : 'active';

        $user = User::updateOrCreate(
            ['email' => $email],
            [
                'name'           => $name,
                'whatsapp_phone' => $phone,
                'address'        => $address,
                'property_id'    => $property->id, // ✅ FORCE VALID PROPERTY
                'password'       => $password
                    ? Hash::make($password)
                    : Hash::make(Str::random(10)),
                'status'         => $status,
            ]
        );

        // ✅ ENSURE TENANT ROLE
        if (! $user->hasRole('tenant')) {
            $user->assignRole('tenant');
        }
    }

    fclose($handle);

    return back()->with('success', 'Tenants imported successfully.');
}






public function show(Request $request, Property $property, User $user)
{
    // ✅ Load ONLY this property's leases (with unit)
    $user->load([
        'leaseAgreements' => function ($q) use ($property) {
            $q->where('property_id', $property->id)
              ->with('unit')
              ->latest('signed_at')
              ->latest('created_at');
        }
    ]);

    // ✅ Latest signed lease (active OR pending)
    $latestSignedLease = $user->leaseAgreements
        ->whereNotNull('signed_at')
        ->whereIn('status', ['active', 'pending'])
        ->first();

    // ✅ Active lease
    $activeLease = $user->leaseAgreements->firstWhere('status', 'active');

    // ✅ Signed flag
    $hasSignedLease = !is_null($latestSignedLease);

    // ✅ Recent leases
    $recentLeases = $user->leaseAgreements->take(5);

    /*
    |--------------------------------------------------------------------------
    | RENT CALENDAR (Jan–Dec) + monthly summaries
    |--------------------------------------------------------------------------
    */
    $year = (int) now()->format('Y');

    // Build months array: ['key' => '2026-01', 'label' => 'January', ...]
    $months = collect(range(1, 12))->map(function ($m) use ($year) {
        $date = Carbon::create($year, $m, 1);
        return [
            'key'   => $date->format('Y-m'),
            'label' => $date->format('F'),
        ];
    })->values()->all();

    // Determine monthly rent (from lease rent_amount, fallback to unit rent_amount)
    $monthlyRent = 0;
    if ($latestSignedLease) {
        $monthlyRent = (float) ($latestSignedLease->rent_amount
            ?? optional($latestSignedLease->unit)->rent_amount
            ?? 0);
    }

$rentSummary = collect();

if ($latestSignedLease) {

    $rows = PropertyPayment::query()
        ->where('property_id', $property->id)
           ->where('lease_agreement_id', $latestSignedLease->id) // ✅ FIX
        ->whereYear('payment_date', $year)
        ->selectRaw('payment_month, SUM(amount) as paid')
        ->groupBy('payment_month')
        ->get();

    $rentSummary = $rows->mapWithKeys(function ($r) use ($monthlyRent) {
        $paid = (float) $r->paid;

        if ($monthlyRent > 0 && $paid >= $monthlyRent) {
            $status = 'paid';
        } elseif ($paid > 0) {
            $status = 'partial';
        } else {
            $status = 'unpaid';
        }

        return [
            $r->payment_month => [
                'paid'   => $paid,
                'status' => $status,
            ]
        ];
    });
}


    return view('properties.users.show', compact(
        'property',
        'user',
        'activeLease',
        'latestSignedLease',
        'recentLeases',
        'hasSignedLease',
        'months',
        'monthlyRent',
        'rentSummary'
    ));
}



    /**
     * Create form
     */
    public function create(Property $property)
    {
        $agent = new Agent();
        $user  = auth()->user();

        if ($agent->isMobile()) {
            return view('properties.users.mobile_create', compact('property', 'user'));
        }
        return view('properties.users.create', compact('property', 'user'));
    }

    /**
     * Store new user under property
     */
    public function store(Request $request, Property $property)
    {
        $data = $request->validate([
            'name'              => 'required|string|max:190',
            'email'             => 'required|email|unique:users,email',
            'password'          => 'required|string|min:6|confirmed',
            'type'              => 'nullable|array',
            'type.*'            => 'string',
            'whatsapp_line'     => 'nullable|string|max:190',
            'whatsapp_phone'    => 'required|string|max:190',
            'address'           => 'nullable|string|max:255',
            'occupation'        => 'nullable|string|max:190',
            'profile_image'     => 'nullable|image|max:2048',
            'camera_image'      => 'nullable|image|max:2048',
            'camera_image_data' => 'nullable|string',
            'roles'             => 'nullable|array',
        ]);

        // Prevent duplicate phone
        if (User::where('whatsapp_phone', $data['whatsapp_phone'])->exists()) {
            return back()->withErrors([
                'whatsapp_phone' => 'This phone number is already in use.',
            ])->withInput();
        }

        $data['type']          = isset($data['type']) ? implode(',', $data['type']) : null;
        $data['profile_image'] = $this->handleImageUpload($request);
        $data['property_id']   = $property->id;
        $data['password']      = Hash::make($data['password']);
        $data['special_code']  = strtoupper(Str::random(6));
        $data['slug']          = Str::slug($data['name']).'-'.Str::random(5);

        $user = User::create($data);

        event(new Registered($user));

        if ($request->filled('roles')) {
            $user->syncRoles($request->roles);
        } else {
            $user->assignRole('tenant'); // default role
        }

        return redirect()->route('properties.users.index', $property->slug)
                         ->with('success','User added successfully!');
    }

    /**
     * Edit form
     */
    public function edit(Property $property, User $user)
    {
        $agent = new Agent();
        if ($agent->isMobile()) {
            return view('properties.users.mobile_edit', compact('property','user'));
        }
        return view('properties.users.edit', compact('property','user'));
    }

    /**
     * Update user
     */
    public function update(Request $request, Property $property, User $user)
    {
        $data = $request->validate([
            'name'              => 'required|string|max:190',
            'email'             => 'required|email|unique:users,email,'.$user->id,
            'password'          => 'nullable|string|min:6|confirmed',
            'type'              => 'nullable|array',
            'type.*'            => 'string',
            'whatsapp_line'     => 'nullable|string|max:190',
            'whatsapp_phone'    => 'nullable|string|max:190',
            'address'           => 'nullable|string|max:255',
            'occupation'        => 'nullable|string|max:190',
            'profile_image'     => 'nullable|image|max:2048',
            'camera_image'      => 'nullable|image|max:2048',
            'camera_image_data' => 'nullable|string',
        ]);

        $data['type'] = isset($data['type']) ? implode(',', $data['type']) : null;

        // Replace image if provided
        if ($request->hasFile('profile_image') || $request->hasFile('camera_image') || $request->filled('camera_image_data')) {
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }
            $data['profile_image'] = $this->handleImageUpload($request);
        }

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('properties.users.index', $property->slug)
                         ->with('success','User updated successfully!');
    }

  public function updateKyc(Request $request, User $user)
{
    $request->validate([
        'kyc_status' => ['required','in:pending,approved,rejected'],
    ]);

    // ✅ Update local KYC status
    $user->update([
        'kyc_status' => $request->input('kyc_status'),
    ]);

    // ✅ Push to QuickBooks via Make webhook if approved
    if ($user->kyc_status === 'approved') {
        try {
            $response = Http::post(config('services.make.webhook_url_kyc'), [
                'name'      => $user->name,
                'email'     => $user->email,
                'address'   => $user->address,
                'city'      => $user->city,
                'country'   => $user->country,
                'phone'     => $user->whatsapp_phone,
                'kycStatus' => $user->kyc_status,
            ]);

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['quickbooks_customer_id'])) {
                    $user->update([
                        'quickbooks_customer_id' => $data['quickbooks_customer_id']
                    ]);
                }
            }
        } catch (\Exception $e) {
            Log::error("QuickBooks sync failed for user {$user->id}: " . $e->getMessage());
        }
    }

    return back()->with('success', "KYC status for {$user->name} updated to {$user->kyc_status}.");
}






    public function destroy(Property $property, User $user)
    {
        if ($user->profile_image) {
            Storage::disk('public')->delete($user->profile_image);
        }

        $user->delete();

        return redirect()->route('property.users.index', $property->slug)
                         ->with('success','User removed.');
    }

    /**
     * Handle image uploads (file/camera/base64)
     */
    protected function handleImageUpload(Request $request)
    {
        if ($request->hasFile('profile_image')) {
            return $request->file('profile_image')->store('users', 'public');
        } elseif ($request->hasFile('camera_image')) {
            return $request->file('camera_image')->store('users', 'public');
        } elseif ($request->filled('camera_image_data')) {
            $image = preg_replace('/^data:image\/\w+;base64,/', '', $request->camera_image_data);
            $image = str_replace(' ', '+', $image);
            $fileName = 'users/' . Str::random(10) . '.png';
            Storage::disk('public')->put($fileName, base64_decode($image));
            return $fileName;
        }
        return null;
    }
}
