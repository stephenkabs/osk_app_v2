<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@osk.app'],
            [
                'name'       => 'System Admin',
                'slug'       => Str::slug('System Admin'),
                'password'   => Hash::make('password'), // change later
                'status'     => 'active',               // ✅ REQUIRED
                'active'     => true,
            ]
        );

        $admin->assignRole('admin');
    }
}
