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

        // 1. Super Admin → ALL Permissions
        $superAdmin = Role::firstOrCreate(
            ['name' => 'super-admin', 'guard_name' => 'web'],
            ['status' => true]
        );
        $superAdmin->syncPermissions(Permission::all());

        // 2. Admin → All clinic permissions (except user/role management)
        $admin = Role::firstOrCreate(
            ['name' => 'admin', 'guard_name' => 'web'],
            ['status' => true]
        );
        $admin->syncPermissions(
            Permission::whereNotIn('group_name', ['Users', 'Roles'])->get()
        );

        // 3. Doctor → Dashboard + Patients + Appointments + View Medicines
        $doctor = Role::firstOrCreate(
            ['name' => 'doctor', 'guard_name' => 'web'],
            ['status' => true]
        );
        $doctor->syncPermissions(
            Permission::whereIn('name', [
                'view-dashboard',
                'view-patients', 'create-patients', 'edit-patients',
                'view-appointments', 'create-appointments', 'edit-appointments',
                'view-medicine-groups', 'view-medicine-names',
                'assign-medicines-to-patients',
                'view-invoices', 'print-invoices',
                
            ])->get()
        );

        // 4. Receptionist → Dashboard + Patients + Appointments (basic)
        $receptionist = Role::firstOrCreate(
            ['name' => 'receptionist', 'guard_name' => 'web'],
            ['status' => true]
        );
        $receptionist->syncPermissions(
            Permission::whereIn('name', [
                'view-dashboard',
                'view-patients', 'create-patients',
                'view-appointments', 'create-appointments', 'edit-appointments',
                'view-invoices', 'create-invoices','send-patient-welcome-email',
                'download-patient-report',
            ])->get()
        );

        // 5. Shipment Department → Shipments + Invoices (view only)
        $shipmentStaff = Role::firstOrCreate(
            ['name' => 'shipment-staff', 'guard_name' => 'web'],
            ['status' => true]
        );
        $shipmentStaff->syncPermissions(
            Permission::whereIn('name', [
                'view-dashboard',
                'view-shipments', 'create-shipments', 'edit-shipments', 'update-shipment-status',
                'view-invoices', 'print-invoices',
                'view-patients', // to see recipient details
            ])->get()
        );

        // 6. Pharmacist → Medicines + Invoices
        $pharmacist = Role::firstOrCreate(
            ['name' => 'pharmacist', 'guard_name' => 'web'],
            ['status' => true]
        );
        $pharmacist->syncPermissions(
            Permission::whereIn('name', [
                'view-dashboard',
                'view-medicine-groups', 'create-medicine-groups', 'edit-medicine-groups',
                'view-medicine-names', 'create-medicine-names', 'edit-medicine-names',
                'view-invoices', 'create-invoices', 'print-invoices',
                'view-patients',
            ])->get()
        );
    }
}