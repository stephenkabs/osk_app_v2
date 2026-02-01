<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Property;
use App\Models\User;
use Illuminate\Support\Str;

class PropertySeeder extends Seeder
{
    public function run(): void
    {
        // ✅ Attach property to ADMIN (or first user)
        $owner = User::whereHas('roles', function ($q) {
            $q->where('name', 'admin');
        })->first();

        // Fallback (in case roles not seeded yet)
        if (! $owner) {
            $owner = User::first();
        }

        if (! $owner) {
            $this->command->warn('No users found. Property seeder skipped.');
            return;
        }

        Property::firstOrCreate(
            [
                'slug' => 'osk-main-property',
            ],
            [
                'user_id'          => $owner->id,   // ✅ FK SAFE
                'property_name'    => 'OSK Main Property',
                'bidding_price'    => 2500000.00,
                'address'          => 'Napsa Complex, Lusaka',
                'property_contact' => '+260773360664',
                'property_email'   => 'info@osk.app',

                // Shares / inventory
                'total_shares'     => 1000,

                // Geo fencing
                'lat'              => -15.416667,
                'lng'              => 28.283333,
                'radius_m'         => 100,

                // QuickBooks placeholders
                'qbo_item_id'      => null,
                'qbo_unit_price'   => null,
                'qbo_qty_on_hand'  => null,

                // Media
                'logo_path'        => null,
                'images'           => null,
            ]
        );
    }
}
