<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Unit;
use App\Models\PropertyPayment;
use App\Models\PropertyExpense;
use App\Models\PropertyLeaseAgreement;
use Barryvdh\DomPDF\Facade\Pdf;


use Carbon\Carbon;

class PropertyReportController extends Controller
{
    /**
     * Property summary report
     */
    public function index(Property $property)
    {
        /*
        |--------------------------------------------------------------------------
        | BASIC COUNTS
        |--------------------------------------------------------------------------
        */

        // Total units (rooms)
        $totalUnits = $property->units()->count();

        // Occupied units (active leases with unit)
        $occupiedUnits = PropertyLeaseAgreement::where('property_id', $property->id)
            ->where('status', 'active')
            ->whereNotNull('unit_id')
            ->distinct('unit_id')
            ->count('unit_id');

        $freeUnits = max($totalUnits - $occupiedUnits, 0);

        // Total lease agreements
        $totalLeases = PropertyLeaseAgreement::where('property_id', $property->id)->count();

        // Active leases
        $activeLeases = PropertyLeaseAgreement::where('property_id', $property->id)
            ->where('status', 'active')
            ->count();

        // Tenants (unique users with leases)
        $totalTenants = PropertyLeaseAgreement::where('property_id', $property->id)
            ->whereNotNull('user_id')
            ->distinct('user_id')
            ->count('user_id');

        /*
        |--------------------------------------------------------------------------
        | FINANCIAL TOTALS
        |--------------------------------------------------------------------------
        */

        // Total payments received
        $totalPayments = PropertyPayment::where('property_id', $property->id)
            ->sum('amount');

        // Total expenses
        $totalExpenses = PropertyExpense::where('property_id', $property->id)
            ->sum('amount');

        // Net balance
        $netBalance = $totalPayments - $totalExpenses;

        /*
        |--------------------------------------------------------------------------
        | OPTIONAL: CURRENT YEAR MONTHLY SUMMARY
        |--------------------------------------------------------------------------
        */

        $year = now()->year;

        $monthlyPayments = PropertyPayment::where('property_id', $property->id)
            ->whereYear('payment_date', $year)
            ->selectRaw('payment_month, SUM(amount) as total')
            ->groupBy('payment_month')
            ->pluck('total', 'payment_month');

        $monthlyExpenses = PropertyExpense::where('property_id', $property->id)
            ->whereYear('expense_date', $year)
            ->selectRaw('DATE_FORMAT(expense_date, "%Y-%m") as month, SUM(amount) as total')
            ->groupBy('month')
            ->pluck('total', 'month');

        return view('properties.reports.index', compact(
            'property',
            'totalUnits',
            'occupiedUnits',
            'freeUnits',
            'totalLeases',
            'activeLeases',
            'totalTenants',
            'totalPayments',
            'totalExpenses',
            'netBalance',
            'monthlyPayments',
            'monthlyExpenses'
        ));
    }




public function exportPdf(Property $property)
{
    // 📊 METRICS
    $totalPayments = PropertyPayment::where('property_id', $property->id)
        ->sum('amount');

    $totalExpenses = PropertyExpense::where('property_id', $property->id)
        ->sum('amount');

    $totalTenants = PropertyLeaseAgreement::where('property_id', $property->id)
        ->where('status', 'active')
        ->count();

    $totalLeases = PropertyLeaseAgreement::where('property_id', $property->id)
        ->count();

    $totalUnits = Unit::where('property_id', $property->id)->count();

    $occupiedUnits = PropertyLeaseAgreement::where('property_id', $property->id)
        ->where('status', 'active')
        ->distinct('unit_id')
        ->count('unit_id');

    $vacantUnits = max($totalUnits - $occupiedUnits, 0);

    $netIncome = $totalPayments - $totalExpenses;

$year = now()->year;

$pdf = Pdf::loadView('properties.reports.pdf', [
    'property'        => $property,
    'year'            => $year,
    'totalPayments'   => $totalPayments,
    'totalExpenses'   => $totalExpenses,
    'netIncome'       => $netIncome,
    'totalUnits'      => $totalUnits,
    'occupiedUnits'   => $occupiedUnits,
    'vacantUnits'     => $vacantUnits,
    'totalTenants'    => $totalTenants,
    'totalLeases'     => $totalLeases,
])->setPaper('a4');

    return $pdf->download(
        'Property-Report-' . $property->property_name . '.pdf'
    );
}

}
