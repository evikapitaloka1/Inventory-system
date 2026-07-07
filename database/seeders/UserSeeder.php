<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Role::where('name', 'admin')->first();
        $staff = Role::where('name', 'staff')->first();
        $manager = Role::where('name', 'manager')->first();

        User::updateOrCreate(
            ['email' => 'admin@inventaris.test'],
            [
                'name' => 'Admin Utama',
                'password' => Hash::make('password'),
                'role_id' => $admin->id,
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'staff@inventaris.test'],
            [
                'name' => 'Budi Staff Gudang',
                'password' => Hash::make('password'),
                'role_id' => $staff->id,
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'manager@inventaris.test'],
            [
                'name' => 'Siti Manager',
                'password' => Hash::make('password'),
                'role_id' => $manager->id,
                'email_verified_at' => now(),
            ]
        );
    }
}
