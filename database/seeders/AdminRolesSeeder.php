<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminRolesSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@example.com',
            'password' => Hash::make('password123'),
            'role' => 'super_admin',
        ]);

        User::create([
            'name' => 'Clerk User',
            'email' => 'clerk@example.com',
            'password' => Hash::make('password123'),
            'role' => 'clerk',
        ]);
    }
}
