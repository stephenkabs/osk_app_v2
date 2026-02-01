<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'user@osk.app'],
            [
                'name'     => 'Default User',
                'slug'     => Str::slug('Default User'),
                'password' => Hash::make('password'),
                'status'   => 'active',
                'active'   => true,
            ]
        );

        $user->assignRole('user');
    }
}
