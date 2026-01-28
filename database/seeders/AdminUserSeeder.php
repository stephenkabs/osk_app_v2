<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = ['admin'];

        foreach ($roles as $role) {

            // Create admin user
            $user = User::create([
                'name'     => ucfirst($role) . ' User',
                'email'    => $role . '@gmail.com',
                'password' => Hash::make('password'),
                'status'   => 'active', // ✅ added
            ]);

            // Create role if it doesn't exist
            $roleModel = Role::firstOrCreate(['name' => $role]);

            // Assign role
            $user->assignRole($roleModel);
        }
    }
}
