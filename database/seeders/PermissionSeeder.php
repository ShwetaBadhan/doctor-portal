<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            // Dashboard
            ['name' => 'view-dashboard',      'group_name' => 'Dashboard'],
            
            // Users
            ['name' => 'view-users',          'group_name' => 'Users'],
            ['name' => 'create-users',        'group_name' => 'Users'],
            ['name' => 'edit-users',          'group_name' => 'Users'],
            ['name' => 'delete-users',        'group_name' => 'Users'],
            
            // Roles & Permissions
            ['name' => 'view-roles',          'group_name' => 'Roles'],
            ['name' => 'create-roles',        'group_name' => 'Roles'],
            ['name' => 'edit-roles',          'group_name' => 'Roles'],
            ['name' => 'delete-roles',        'group_name' => 'Roles'],
            ['name' => 'assign-permissions',  'group_name' => 'Roles'],
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(
                ['name' => $perm['name'], 'guard_name' => 'web'],
                ['group_name' => $perm['group_name'], 'status' => true]
            );
        }
    }
}