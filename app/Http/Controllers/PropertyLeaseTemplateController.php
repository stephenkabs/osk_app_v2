<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\LeaseTemplate;
use Illuminate\Http\Request;

class PropertyLeaseTemplateController extends Controller
{
    /**
     * Edit lease template for a property
     */
    public function edit(Property $property)
    {
        // Create template if it does not exist
        $template = $property->leaseTemplate()->firstOrCreate(
            ['property_id' => $property->id],
            [
                'title' => 'Standard Lease Agreement',
                'terms' => $this->defaultTemplate($property),
            ]
        );

        return view('properties.lease_template.edit', compact('property', 'template'));
    }

    /**
     * Update lease template
     */
    public function update(Request $request, Property $property)
    {
        $data = $request->validate([
            'title' => 'required|string|max:190',
            'terms' => 'required|string',
        ]);

        $property->leaseTemplate()->updateOrCreate(
            ['property_id' => $property->id],
            $data
        );

        return back()->with('success', 'Lease template updated successfully.');
    }

    /**
     * Default lease wording (used on first creation)
     */
private function defaultTemplate(Property $property): string
{
    $address = $property->address ?: 'the specified property';

    return <<<TEXT
1. Parties
The landlord is {$property->property_name} (“Landlord”) and the tenant (“Tenant”).

2. Property
The leased premises are located at {$address}.

3. Term
The lease begins on the agreed start date and continues until terminated in accordance with this agreement.

4. Rent
Rent is payable monthly in advance on or before the 1st day of each month.

5. Deposits
A refundable security deposit equivalent to one month’s rent is required.

6. Termination
Either party may terminate this agreement by giving one month’s written notice.

TEXT;
}

}
