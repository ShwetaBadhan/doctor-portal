<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Admin → All Permissions
        $admin = Role::firstOrCreate(
            ['name' => 'admin', 'guard_name' => 'web'],
            ['status' => true]
        );
        $admin->syncPermissions(Permission::all());

        // 2. Doctor → Dashboard only (customize as needed)
        $doctor = Role::firstOrCreate(
            ['name' => 'doctor', 'guard_name' => 'web'],
            ['status' => true]
        );
        $doctor->syncPermissions(
            Permission::where('group_name', 'Dashboard')->get()
        );

        // 3. Receptionist → Dashboard + Basic User View
        $receptionist = Role::firstOrCreate(
            ['name' => 'receptionist', 'guard_name' => 'web'],
            ['status' => true]
        );
        $receptionist->syncPermissions(
            Permission::whereIn('name', ['view-dashboard', 'view-users', 'create-users'])->get()
        );
    }
}