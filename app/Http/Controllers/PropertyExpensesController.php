<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\PropertyExpense;
use Illuminate\Http\Request;

class PropertyExpensesController extends Controller
{
    /**
     * List expenses
     */
    public function index(Property $property)
    {
        $expenses = PropertyExpense::with(['unit','recorder'])
            ->where('property_id', $property->id)
            ->latest('expense_date')
            ->paginate(20);

        $units = $property->units()
            ->orderBy('code')
            ->get();

        return view('properties.expenses.index', compact(
            'property',
            'expenses',
            'units'
        ));
    }

    /**
     * Store expense
     */
    public function store(Request $request, Property $property)
    {
        $data = $request->validate([
            'unit_id'        => 'nullable|exists:units,id',
            'category'       => 'required|string|max:50',
            'title'          => 'required|string|max:190',
            'description'    => 'nullable|string',
            'amount'         => 'required|numeric|min:0.01',
            'expense_date'   => 'required|date',
            'payment_method' => 'nullable|string|max:50',
            'reference'      => 'nullable|string|max:190',
        ]);

        PropertyExpense::create([
            ...$data,
            'property_id' => $property->id,
            'recorded_by' => auth()->id(),
        ]);

        return back()->with('success', 'Expense recorded successfully.');
    }

    /**
     * Show single expense
     */
    public function show(Property $property, PropertyExpense $expense)
    {
        abort_unless($expense->property_id === $property->id, 404);

        return view('properties.expenses.show', compact(
            'property',
            'expense'
        ));
    }

    /**
     * Edit expense
     */
    public function edit(Property $property, PropertyExpense $expense)
    {
        abort_unless($expense->property_id === $property->id, 404);

        $units = $property->units()->orderBy('code')->get();

        return view('properties.expenses.edit', compact(
            'property',
            'expense',
            'units'
        ));
    }

    /**
     * Update expense
     */
    public function update(Request $request, Property $property, PropertyExpense $expense)
    {
        abort_unless($expense->property_id === $property->id, 404);

        $data = $request->validate([
            'unit_id'        => 'nullable|exists:units,id',
            'category'       => 'required|string|max:50',
            'title'          => 'required|string|max:190',
            'description'    => 'nullable|string',
            'amount'         => 'required|numeric|min:0.01',
            'expense_date'   => 'required|date',
            'payment_method' => 'nullable|string|max:50',
            'reference'      => 'nullable|string|max:190',
        ]);

        $expense->update($data);

        return redirect()
            ->route('property.expenses.index', $property->slug)
            ->with('success', 'Expense updated successfully.');
    }

    /**
     * Delete expense
     */
    public function destroy(Property $property, PropertyExpense $expense)
    {
        abort_unless($expense->property_id === $property->id, 404);

        $expense->delete();

        return back()->with('success', 'Expense deleted.');
    }
}
