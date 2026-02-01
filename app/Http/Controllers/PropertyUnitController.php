<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PropertyUnitController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
        // $this->middleware('permission:units.manage'); // if using Spatie permissions
    }

    public function index(Property $property)
    {
        $units = $property->units()->latest()->paginate(24);
        return view('properties.units.index', compact('property', 'units'));
    }

    public function create(Property $property)
    {
        return view('properties.units.create', compact('property'));
    }

    // public function store(Request $request, Property $property)
    // {
    //     $data = $request->validate([
    //         'code'            => ['required','string','max:100'],
    //         'type'            => ['nullable','string','max:100'],
    //         'floor'           => ['nullable','integer','min:-10','max:200'],
    //         'bedrooms'        => ['nullable','integer','min:0','max:50'],
    //         'bathrooms'       => ['nullable','integer','min:0','max:50'],
    //         'rent_amount'     => ['nullable','numeric','min:0'],
    //         'deposit_amount'  => ['nullable','numeric','min:0'],
    //         'size_sq_m'       => ['nullable','numeric','min:0'],
    //         'status'          => ['nullable','in:available,occupied,maintenance'],
    //         'photo'           => ['nullable','image','max:4096'],
    //         'notes'           => ['nullable','string','max:2000'],
    //     ]);

    //     // Unique code within property
    //     if ($property->units()->where('code', $data['code'])->exists()) {
    //         return back()->withErrors(['code' => 'That unit code already exists for this property.'])->withInput();
    //     }

    //     $data['property_id'] = $property->id;

    //     if ($request->hasFile('photo')) {
    //         $data['photo_path'] = $request->file('photo')->store('units', 'public');
    //     }

    //     // Optional: allow incoming slug override; else generate from code
    //     $data['slug'] = Str::slug($data['code']).'-'.Str::random(4);

    //     $unit = Unit::create($data);

    //     return redirect()
    //         ->route('property.units.index', $property->slug)
    //         ->with('success', 'Unit created successfully.');
    // }


public function exportCsv(Property $property)
{
    $fileName = 'units_'.$property->slug.'_'.now()->format('Ymd_His').'.csv';

    return response()->stream(function () use ($property) {
        $file = fopen('php://output', 'w');

        // CSV headers
        fputcsv($file, [
            'code',
            'type',
            'floor',
            'bedrooms',
            'bathrooms',
            'rent_amount',
            'deposit_amount',
            'size_sq_m',
            'status',
            'notes',
        ]);

        $property->units()->orderBy('code')->each(function ($unit) use ($file) {
            fputcsv($file, [
                $unit->code,
                $unit->type,
                $unit->floor,
                $unit->bedrooms,
                $unit->bathrooms,
                $unit->rent_amount,
                $unit->deposit_amount,
                $unit->size_sq_m,
                $unit->status,
                $unit->notes,
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

    $header = fgetcsv($handle); // skip header

    while (($row = fgetcsv($handle)) !== false) {

        [
            $code,
            $type,
            $floor,
            $bedrooms,
            $bathrooms,
            $rent,
            $deposit,
            $size,
            $status,
            $notes
        ] = array_pad($row, 10, null);

        Unit::updateOrCreate(
            [
                'property_id' => $property->id,
                'code'        => trim($code),
            ],
            [
                'type'           => $type ?: null,
                'floor'          => $floor !== '' ? (int) $floor : null,
                'bedrooms'       => $bedrooms !== '' ? (int) $bedrooms : null,
                'bathrooms'      => $bathrooms !== '' ? (int) $bathrooms : null,
                'rent_amount'    => $rent !== '' ? (float) $rent : null,
                'deposit_amount' => $deposit !== '' ? (float) $deposit : null,
                'size_sq_m'      => $size !== '' ? (float) $size : null,
                'status'         => $status ?: 'available',
                'notes'          => $notes ?: null,
            ]
        );
    }

    fclose($handle);

    return back()->with('success', 'Units imported successfully.');
}



    public function store(Request $request, Property $property)
{
    $units = $request->input('units');

    if (!$units || !is_array($units)) {
        return back()->withErrors(['units' => 'No units provided']);
    }

    foreach ($units as $index => $unitData) {

        if ($property->units()->where('code', $unitData['code'])->exists()) {
            continue; // or throw error if you want strict mode
        }

        $data = [
            'property_id'     => $property->id,
            'code'            => $unitData['code'],
            'rent_amount'     => $unitData['rent_amount'] ?? null,
            'deposit_amount'  => $unitData['deposit_amount'] ?? null,
            'status'          => 'available',
            'slug'            => Str::slug($unitData['code']).'-'.Str::random(4),
        ];

        if ($request->hasFile("units.$index.photo")) {
            $data['photo_path'] =
                $request->file("units.$index.photo")->store('units', 'public');
        }

        Unit::create($data);
    }

    return redirect()
        ->route('property.units.index', $property->slug)
        ->with('success', 'Units created successfully.');
}

    public function show(Property $property, Unit $unit)
    {
        // When using {unit:slug} in route, Laravel ensures unit exists.
        // Optionally guard property->id == unit->property_id
        abort_unless($unit->property_id === $property->id, 404);

        return view('properties.units.show', compact('property','unit'));
    }

    public function edit(Property $property, Unit $unit)
    {
        abort_unless($unit->property_id === $property->id, 404);
        return view('properties.units.edit', compact('property','unit'));
    }

    public function update(Request $request, Property $property, Unit $unit)
    {
        abort_unless($unit->property_id === $property->id, 404);

        $data = $request->validate([
            'code'            => ['required','string','max:100'],
            'type'            => ['nullable','string','max:100'],
            'floor'           => ['nullable','integer','min:-10','max:200'],
            'bedrooms'        => ['nullable','integer','min:0','max:50'],
            'bathrooms'       => ['nullable','integer','min:0','max:50'],
            'rent_amount'     => ['nullable','numeric','min:0'],
            'deposit_amount'  => ['nullable','numeric','min:0'],
            'size_sq_m'       => ['nullable','numeric','min:0'],
            'status'          => ['nullable','in:available,occupied,maintenance'],
            'photo'           => ['nullable','image','max:4096'],
            'notes'           => ['nullable','string','max:2000'],
        ]);

        // Unique code within property (ignore current unit)
        if ($property->units()
                     ->where('code', $data['code'])
                     ->where('id', '!=', $unit->id)
                     ->exists()) {
            return back()->withErrors(['code' => 'That unit code already exists for this property.'])->withInput();
        }

        if ($request->hasFile('photo')) {
            if ($unit->photo_path) {
                Storage::disk('public')->delete($unit->photo_path);
            }
            $data['photo_path'] = $request->file('photo')->store('units', 'public');
        }

        $unit->update($data);

        return redirect()
            ->route('property.units.index', $property->slug)
            ->with('success', 'Unit updated successfully.');
    }

    public function destroy(Property $property, Unit $unit)
    {
        abort_unless($unit->property_id === $property->id, 404);

        if ($unit->photo_path) {
            Storage::disk('public')->delete($unit->photo_path);
        }

        $unit->delete();

        return redirect()
            ->route('property.units.index', $property->slug)
            ->with('success', 'Unit deleted.');
    }
}
