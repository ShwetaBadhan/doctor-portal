<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\PermissionRegistrar;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Super Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@doctorportal.com'],
            [
                'name'              => 'System Administrator',
                'phone'             => '9876543210',
                'password'          => Hash::make('Admin@123'),
                'status'            => true,
                'email_verified_at' => now(),
            ]
        );
        $admin->assignRole('admin');

        // 2. Doctor
        $doctor = User::firstOrCreate(
            ['email' => 'doctor@doctorportal.com'],
            [
                'name'              => 'Dr. Sarah Smith',
                'phone'             => '9876543211',
                'password'          => Hash::make('Doctor@123'),
                'status'            => true,
                'email_verified_at' => now(),
            ]
        );
        $doctor->assignRole('doctor');

        // 3. Receptionist
        $receptionist = User::firstOrCreate(
            ['email' => 'reception@doctorportal.com'],
            [
                'name'              => 'Reception Desk',
                'phone'             => '9876543212',
                'password'          => Hash::make('Reception@123'),
                'status'            => true,
                'email_verified_at' => now(),
            ]
        );
        $receptionist->assignRole('receptionist');
    }
}