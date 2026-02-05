<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use App\Models\PropertyPayment;
use Illuminate\Support\Facades\Auth;
use App\Models\Property;
use App\Models\Unit;
use App\Models\PropertyLeaseAgreement;


class AdminController extends Controller
{




// public function index(Request $request)
// {
//     if (!Auth::check()) {
//         return redirect()->route('login');
//     }

//     $user = Auth::user();

//     /* ===============================
//        LANDLORD DASHBOARD
//     =============================== */
//     if ($user->hasRole('landlord')) {

//         $hasProperty = Property::where('user_id', $user->id)->exists();

//         if (! $hasProperty) {
//             return redirect()->route('properties.create_form');
//         }

//         return redirect()->route('dashboard.landlord');
//     }

//     /* ===============================
//        TENANT DASHBOARD
//     =============================== */
//     if ($user->hasRole('tenant')) {
//         return redirect()->route('dashboard.tenant');
//     }

// /* ===============================
//    MANAGER DASHBOARD
// =============================== */
// if ($user->hasRole('manager')) {

//     // since you have only ONE property
//     $property = Property::latest()->first();

//     if (! $property) {
//         abort(404, 'No property found');
//     }

//     // 👉 SHOW THE SAME DASHBOARD VIEW
//     return view('dashboard.landlord', compact('property'));
// }




//     /* ===============================
//        INVESTOR
//     =============================== */
//     if ($user->hasRole('investor')) {
//         return redirect()->route('dashboard.investor');
//     }

//     /* ===============================
//        ADMIN / STAFF DASHBOARD
//     =============================== */
//  $pendingClientsCount = User::where('property_id', $property->id)
//     ->whereHas('leases', function ($q) {
//         $q->where('status', '!=', 'active');
//     })
//     ->orWhereDoesntHave('leases')
//     ->count();



// $property = Property::latest()->first(); // or choose logic you want

// $month = $request->get('month', now()->format('Y-m'));

// $rows = [];
// $totals = [
//     'lettable' => 0,
//     'rent'     => 0,
//     'paid'     => 0,
//     'overdue'  => 0,
// ];

// if ($property) {

//     $leases = PropertyLeaseAgreement::with(['user', 'unit', 'payments'])
//         ->where('property_id', $property->id)
//         ->whereIn('status', ['pending','active'])
//         ->get();

//     foreach ($leases as $lease) {
//         $rent = $lease->rent_amount;
//         $paid = $lease->payments
//             ->where('payment_month', $month)
//             ->sum('amount');

//         $overdue = max($rent - $paid, 0);

//         $rows[] = [
//             'room'       => optional($lease->unit)->code,
//             'tenant'     => optional($lease->user)->name,
//             'lease'      => 'Yes',
//             'entry_date' => $lease->start_date,
//             'rent'       => $rent,
//             'paid'       => $paid,
//             'overdue'    => $overdue,
//             'balance'    => $overdue,
//         ];

//         $totals['rent'] += $rent;
//         $totals['paid'] += $paid;
//         $totals['overdue'] += $overdue;
//     }
// }
// return view('dashboard.index', compact(
//     'user',
//     'pendingClientsCount',
//     'property',
//     'rows',
//     'totals',
//     'month'
// ));

// }



public function index(Request $request)
{
    if (!Auth::check()) {
        return redirect()->route('login');
    }

    $user = Auth::user();

    /* ===============================
       ROLE REDIRECTS
    =============================== */
    if ($user->hasRole('landlord')) {
        if (!Property::where('user_id', $user->id)->exists()) {
            return redirect()->route('properties.create_form');
        }
        return redirect()->route('dashboard.landlord');
    }

    if ($user->hasRole('tenant')) {
        return redirect()->route('dashboard.tenant');
    }

    if ($user->hasRole('manager')) {
        $property = Property::latest()->first();
        abort_unless($property, 404);
        return view('dashboard.landlord', compact('property'));
    }

    if ($user->hasRole('investor')) {
        return redirect()->route('dashboard.investor');
    }

    /* ===============================
       ADMIN DASHBOARD
    =============================== */

    $property = Property::latest()->first();
    abort_unless($property, 404);

    $pendingClientsCount = User::where('property_id', $property->id)
        ->where(function ($q) {
            $q->whereHas('leases', function ($l) {
                $l->where('status', '!=', 'active');
            })
            ->orWhereDoesntHave('leases');
        })
        ->count();



$property = Property::latest()->first(); // or choose logic you want

$month = $request->get('month', now()->format('Y-m'));

$rows = [];
$totals = [
    'lettable' => Unit::where('property_id', $property->id)->sum('rent_amount'),
    'rent'     => 0,
    'paid'     => 0,
    'overdue'  => 0,
    'balance'  => 0,
    'empty'    => 0, // ✅ ADD THIS
];


/* ===============================
   LEASED ROOMS
=============================== */
$leases = PropertyLeaseAgreement::with([
    'user',
    'unit',
    'payments' => function ($q) use ($month) {
        $q->where('payment_month', $month);
    }
])
->where('property_id', $property->id)
->whereIn('status', ['pending','active'])
->get();

foreach ($leases as $lease) {

    $rent = (float) $lease->rent_amount;
    $paid = (float) $lease->payments->sum('amount');
    $overdue = max($rent - $paid, 0);

    $rows[] = [
        'room'       => optional($lease->unit)->code ?? '—',
        'tenant'     => optional($lease->user)->name ?? '—',
        'lease'      => $lease,
        'entry_date' => $lease->start_date,
        'rent'       => $rent,
        'paid'       => $paid,
        'overdue'    => $overdue,
        'balance'    => $overdue,
    ];



    // Other totals
    $totals['rent']    += $rent;
    $totals['paid']    += $paid;
    $totals['overdue'] += $overdue;
    $totals['balance'] += $overdue;
}


/* ===============================
   EMPTY ROOMS
=============================== */
$occupiedUnitIds = $leases
    ->pluck('unit_id')
    ->filter()
    ->values();

$emptyUnits = Unit::where('property_id', $property->id)
    ->whereNotIn('id', $occupiedUnitIds)
    ->get();

foreach ($emptyUnits as $unit) {

    $rows[] = [
        'room'       => $unit->code,
        'tenant'     => '—',
        'lease'      => null, // 👈 IMPORTANT
        'entry_date' => null,
        'rent'       => (float) ($unit->rent_amount ?? 0),
        'paid'       => 0,
        'overdue'    => 0,
        'balance'    => 0,
    ];

    $totals['empty'] += (float) ($unit->rent_amount ?? 0);
}

return view('dashboard.index', compact(
    'user',
    'pendingClientsCount',
    'property',
    'rows',
    'totals',
    'month'
));

}



public function landlordDashboard()
{
    $user = Auth::user();

    // Get landlord's primary property (latest or first)
    $property = Property::where('user_id', $user->id)->first();

    // Safety fallback (should not normally happen)
    // if (! $property) {
    //     return redirect()->route('properties.create_form');
    // }

    return view('dashboard.landlord', compact('property'));
}




public function investorDashboard()
{
    $user = Auth::user();

    /* =======================
       INVESTOR ROLE CHECK
    ======================== */
    if (! $user->hasRole('investor')) {
        abort(403, 'Unauthorized');
    }

    /* =======================
       PARTNER PROFILE CHECK
    ======================== */
    $partner = $user->partner;

    if (! $partner) {
        return redirect()
            ->route('partners.create')
            ->with('warning', 'Please create your investor profile first.');
    }

    if ($partner->status !== 'approved') {
        return redirect()
            ->route('partners.show', $partner->slug)
            ->with('warning', 'Your investor profile is pending approval.');
    }

    /* =======================
       STARTER DASHBOARD DATA
    ======================== */

    $totalInvested  = 0;
    $totalReturns  = 0;
    $monthlyIncome = collect();

    // ✅ LOAD AVAILABLE PROPERTIES (from QBO sync)
    $properties = \App\Models\Property::latest()->get();

    return view('dashboard.investor', compact(
        'user',
        'partner',
        'totalInvested',
        'totalReturns',
        'monthlyIncome',
        'properties'
    ));
}









public function tenantDashboard()
{
    $user = Auth::user();

    // Load tenant agreements + unit
    $user->load([
        'leaseAgreements' => function ($q) {
            $q->with(['property', 'unit'])
              ->latest('signed_at')
              ->latest('created_at');
        }
    ]);

    // Latest signed lease (active OR pending)
    $latestSignedLease = $user->leaseAgreements
        ->whereNotNull('signed_at')
        ->whereIn('status', ['active', 'pending'])
        ->first();

    if (! $latestSignedLease) {
        return view('dashboard.tenant_empty', compact('user'));
    }

    $property = $latestSignedLease->property;

    // Active lease flag
    $activeLease = $user->leaseAgreements->firstWhere('status', 'active');
    $hasSignedLease = true;

    /*
    |--------------------------------------------------------------------------
    | RENT CALENDAR (Jan–Dec)
    |--------------------------------------------------------------------------
    */
    $year = (int) now()->format('Y');

    $months = collect(range(1, 12))->map(function ($m) use ($year) {
        $date = Carbon::create($year, $m, 1);
        return [
            'key'   => $date->format('Y-m'),
            'label' => $date->format('F'),
        ];
    })->values()->all();

    // Monthly rent
    $monthlyRent = (float) (
        $latestSignedLease->rent_amount
        ?? optional($latestSignedLease->unit)->rent_amount
        ?? 0
    );

    /*
    |--------------------------------------------------------------------------
    | RENT SUMMARY
    |--------------------------------------------------------------------------
    */
    $rentSummary = collect();

    if ($latestSignedLease) {
        $rows = PropertyPayment::query()
            ->where('property_id', $property->id)
            ->where('lease_agreement_id', $latestSignedLease->id)
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

    return view('dashboard.tenant', compact(
        'user',
        'property',
        'latestSignedLease',
        'activeLease',
        'hasSignedLease',
        'months',
        'monthlyRent',
        'rentSummary'
    ));
}





    public function logout(Request $request)
    {
        Auth::logout(); // Logs out the user

        $request->session()->invalidate(); // Invalidates the session
        $request->session()->regenerateToken(); // Regenerates the CSRF token

        return redirect('/login'); // Redirect to the login page
    }

}
