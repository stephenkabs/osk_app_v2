<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ManagerUserSeeder extends Seeder
{
    public function run(): void
    {
        $manager = User::firstOrCreate(
            ['email' => 'manager@osk.app'],
            [
                'name'     => 'Property Manager',
                'slug'     => Str::slug('Property Manager'),
                'password' => Hash::make('password'),
                'status'   => 'active',
                'active'   => true,
            ]
        );

        $manager->assignRole('manager');
    }
}
