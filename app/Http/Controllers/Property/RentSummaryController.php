<?php

namespace App\Http\Controllers\Property;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\PropertyLeaseAgreement;
use App\Models\Unit;
use Illuminate\Http\Request;
use Carbon\Carbon;


class RentSummaryController extends Controller
{

public function index(Property $property, Request $request)
{
    $month = $request->get('month', now()->format('Y-m'));

    /* ===============================
       ACTIVE / PENDING LEASES
    =============================== */
    $leases = PropertyLeaseAgreement::with([
        'user',   // tenant
        'unit',
        'payments' => function ($q) use ($month) {
            $q->where('payment_month', $month);
        }
    ])
    ->where('property_id', $property->id)
    ->whereIn('status', ['pending', 'active'])
    ->get();

    $rows = [];

    $totals = [
        'lettable' => 0,
        'rent'     => 0,
        'paid'     => 0,
        'overdue'  => 0,
        'balance'  => 0,
        'empty'    => 0,
    ];

    foreach ($leases as $lease) {

        $rent = (float) $lease->rent_amount;
        $paid = (float) $lease->payments->sum('amount');
        $overdue = max($rent - $paid, 0);

        $rows[] = [
            'room'       => optional($lease->unit)->code ?? '—',
            'tenant'     => optional($lease->user)->name ?? '—',
            'lease'      => 'Yes',
            'entry_date' => $lease->start_date,
            'rent'       => $rent,
            'paid'       => $paid,
            'overdue'    => $overdue,
            'balance'    => $overdue,
        ];

        $totals['lettable'] += $rent;
        $totals['rent']     += $rent;
        $totals['paid']     += $paid;
        $totals['overdue']  += $overdue;
        $totals['balance'] += $overdue;
    }

    /* ===============================
       EMPTY UNITS
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
            'lease'      => 'No',
            'entry_date' => null,
            'rent'       => (float) ($unit->rent_amount ?? 0),
            'paid'       => 0,
            'overdue'    => 0,
            'balance'    => 0,
        ];

        $totals['empty'] += (float) ($unit->rent_amount ?? 0);
    }

    return view('properties.rent-summary.index', [
        'property' => $property,
        'rows'     => $rows,
        'totals'   => $totals,
        'month'    => $month,
    ]);
}

}
