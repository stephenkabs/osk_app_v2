<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\PropertyLeaseAgreement;
use App\Models\PropertyPayment;
use Carbon\Carbon;

    use Barryvdh\DomPDF\Facade\Pdf;


use Illuminate\Http\Request;

class PropertyPaymentsController extends Controller
{
    /**
     * Landlord payments list
     */
    public function index(Property $property)
    {
        $payments = PropertyPayment::with(['lease.unit','tenant'])
            ->where('property_id', $property->id)
            ->latest('payment_date')
            ->paginate(20);

        $leases = PropertyLeaseAgreement::with(['tenant','unit'])
            ->where('property_id', $property->id)
            ->where('status', 'active')
            ->get();



        return view('properties.payments.index', compact(
            'property',
            'payments',
            'leases'
        ));
    }




public function receipt(Property $property, PropertyLeaseAgreement $lease, string $month)
{
    // 🔒 Safety checks
    abort_unless($lease->property_id === $property->id, 404);

    // Month parsing (YYYY-MM)
    try {
        $monthDate = Carbon::createFromFormat('Y-m', $month);
    } catch (\Exception $e) {
        abort(404, 'Invalid month.');
    }

    // All payments for that lease + month
    $payments = PropertyPayment::where('property_id', $property->id)
        ->where('lease_agreement_id', $lease->id)
        ->where('payment_month', $month)
        ->orderBy('payment_date')
        ->get();

    if ($payments->isEmpty()) {
        abort(404, 'No payments found for this month.');
    }

    // Totals
    $paidTotal = $payments->sum('amount');

    $monthlyRent = (float) (
        $lease->rent_amount ??
        optional($lease->unit)->rent_amount ??
        0
    );

    $balance = max($monthlyRent - $paidTotal, 0);

    // Generate PDF
    $pdf = Pdf::loadView('properties.payments.receipt', [
        'property'     => $property,
        'lease'        => $lease,
        'tenant'       => $lease->tenant,
        'unit'         => $lease->unit,
        'payments'     => $payments,
        'month'        => $monthDate,
        'monthlyRent'  => $monthlyRent,
        'paidTotal'    => $paidTotal,
        'balance'      => $balance,
    ])->setPaper('a4');

    $filename = 'Rent-Receipt-' .
        ($lease->tenant->name ?? 'Tenant') . '-' .
        $monthDate->format('Y-m');

    return $pdf->download($filename . '.pdf');
}


    /**
     * Store a rent payment (manual recording)
     */
    public function store(Request $request, Property $property)
    {
        $data = $request->validate([
          'lease_id' => ['required','exists:property_lease_agreements,id'],
            'payment_month' => ['required','date_format:Y-m'],
            'amount'        => ['required','numeric','min:0.01'],
            'method'        => ['nullable','string'],
            'payment_date'  => ['required','date'],
            'reference'     => ['nullable','string','max:190'],
        ]);

        /** @var PropertyLeaseAgreement $lease */
        $lease = PropertyLeaseAgreement::with('tenant')
            ->where('property_id', $property->id)
            ->findOrFail($data['lease_id']);

        if (!$lease->tenant) {
            return back()->withErrors([
                'lease_id' => 'This lease has no tenant assigned.'
            ]);
        }

        // ✅ Calculate rent due for the selected month
        $rentDue = $this->rentDueForMonth($lease, $data['payment_month']);

        // ✅ Already paid this month
        $paidSoFar = PropertyPayment::where('lease_agreement_id', $lease->id)
            ->where('payment_month', $data['payment_month'])
            ->sum('amount');

        $newTotal = $paidSoFar + $data['amount'];

        // ✅ Payment status
        $status = match (true) {
            $newTotal >= $rentDue => 'paid',
            $newTotal > 0         => 'partial',
            default               => 'pending',
        };

        PropertyPayment::create([
            'property_id'   => $property->id,
            'lease_agreement_id'  => $lease->id,   // ✅ FIX
            'user_id'       => $lease->tenant->id,
            'payment_month' => $data['payment_month'],
            'payment_date'  => $data['payment_date'],
            'amount'        => $data['amount'],
            'method'        => $data['method'] ?? 'cash',
            'reference'     => $data['reference'],
            'status'        => $status,
            'recorded_by'   => auth()->id(),
        ]);

        return back()->with('success', 'Payment recorded successfully.');
    }

    /**
     * Calculate rent due for a specific month
     */
    protected function rentDueForMonth(
        PropertyLeaseAgreement $lease,
        string $paymentMonth
    ): float {
        $startDate   = Carbon::parse($lease->start_date);
        $targetMonth = Carbon::createFromFormat('Y-m', $paymentMonth)->startOfMonth();

        // ✅ First lease month → PRORATED
        if ($targetMonth->isSameMonth($startDate)) {
            return $this->calculateProratedAmount(
                (float) $lease->rent_amount,
                $startDate
            );
        }

        // ✅ All other months → FULL rent
        return (float) $lease->rent_amount;
    }

    /**
     * Prorate first month rent
     */
    protected function calculateProratedAmount(
        float $monthlyRent,
        Carbon $startDate
    ): float {
        $daysInMonth   = $startDate->daysInMonth;
        $dayOfMonth    = $startDate->day;
        $daysRemaining = ($daysInMonth - $dayOfMonth) + 1;

        $dailyRate = $monthlyRent / $daysInMonth;

        return round($dailyRate * $daysRemaining, 2);
    }


    public function edit(Property $property, PropertyPayment $payment)
{
    abort_unless($payment->property_id === $property->id, 404);

    $payment->load(['lease.unit', 'tenant']);

    return view('properties.payments.edit', compact(
        'property',
        'payment'
    ));
}
public function update(
    Request $request,
    Property $property,
    PropertyPayment $payment
) {
    abort_unless($payment->property_id === $property->id, 404);

    $data = $request->validate([
        'amount'       => ['required','numeric','min:0.01'],
        'payment_date' => ['required','date'],
        'method'       => ['nullable','string'],
        'reference'    => ['nullable','string','max:190'],
    ]);

    $payment->update($data);

    return redirect()
        ->route('property.payments.index', $property->slug)
        ->with('success', 'Payment updated successfully.');
}

public function destroy(Property $property, PropertyPayment $payment)
{
    abort_unless($payment->property_id === $property->id, 404);

    $payment->delete();

    return back()->with('success', 'Payment deleted.');
}

}
