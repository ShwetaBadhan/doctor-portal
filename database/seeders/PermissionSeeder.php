<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            // Dashboard
            ['name' => 'view-dashboard', 'group_name' => 'Dashboard'],
            
            // Users
            ['name' => 'view-users', 'group_name' => 'Users'],
            ['name' => 'create-users', 'group_name' => 'Users'],
            ['name' => 'edit-users', 'group_name' => 'Users'],
            ['name' => 'delete-users', 'group_name' => 'Users'],
            
            // Roles & Permissions
            ['name' => 'view-roles', 'group_name' => 'Roles'],
            ['name' => 'create-roles', 'group_name' => 'Roles'],
            ['name' => 'edit-roles', 'group_name' => 'Roles'],
            ['name' => 'delete-roles', 'group_name' => 'Roles'],
            ['name' => 'assign-permissions', 'group_name' => 'Roles'],
            
            // Patients
            ['name' => 'view-patients', 'group_name' => 'Patients'],
            ['name' => 'create-patients', 'group_name' => 'Patients'],
            ['name' => 'edit-patients', 'group_name' => 'Patients'],
            ['name' => 'delete-patients', 'group_name' => 'Patients'],
            ['name' => 'assign-medicines-to-patients', 'group_name' => 'Patients'],
            ['name' => 'view-patient-reports', 'group_name' => 'Patients'],
            ['name' => 'upload-patient-reports', 'group_name' => 'Patients'],
            ['name' => 'view-patient-details', 'group_name' => 'Patients'],
            ['name' => 'view-patient-appointment', 'group_name' => 'Patients'],
            ['name' => 'download-patient-reports', 'group_name' => 'Patients'],
            ['name' => 'delete-patient-reports', 'group_name' => 'Patients'],
            
            // Appointments
            ['name' => 'view-appointments', 'group_name' => 'Appointments'],
            ['name' => 'view-appointment-details', 'group_name' => 'Appointments'],
            ['name' => 'create-appointments', 'group_name' => 'Appointments'],
            ['name' => 'edit-appointments', 'group_name' => 'Appointments'],
            ['name' => 'delete-appointments', 'group_name' => 'Appointments'],
            ['name' => 'manage-appointment-calendar', 'group_name' => 'Appointments'],
            
            // Medicines (Groups & Master List)
            ['name' => 'view-medicine-groups', 'group_name' => 'Medicines'],
            ['name' => 'view-medicine-group-details', 'group_name' => 'Medicines'],
            ['name' => 'create-medicine-groups', 'group_name' => 'Medicines'],
            ['name' => 'edit-medicine-groups', 'group_name' => 'Medicines'],
            ['name' => 'delete-medicine-groups', 'group_name' => 'Medicines'],
            ['name' => 'view-medicine-names', 'group_name' => 'Medicines'],
            ['name' => 'create-medicine-names', 'group_name' => 'Medicines'],
            ['name' => 'edit-medicine-names', 'group_name' => 'Medicines'],
            ['name' => 'delete-medicine-names', 'group_name' => 'Medicines'],
            
            // Invoices
            ['name' => 'view-invoices', 'group_name' => 'Invoices'],
            ['name' => 'create-invoices', 'group_name' => 'Invoices'],
            ['name' => 'edit-invoices', 'group_name' => 'Invoices'],
            ['name' => 'delete-invoices', 'group_name' => 'Invoices'],
            ['name' => 'print-invoices', 'group_name' => 'Invoices'],
            
            // Shipments
            ['name' => 'view-shipments', 'group_name' => 'Shipments'],
            ['name' => 'create-shipments', 'group_name' => 'Shipments'],
            ['name' => 'edit-shipments', 'group_name' => 'Shipments'],
            ['name' => 'delete-shipments', 'group_name' => 'Shipments'],
            ['name' => 'update-shipment-status', 'group_name' => 'Shipments'],
            ['name' => 'view-shipment-dashboard', 'group_name' => 'Shipments'],
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(
                ['name' => $perm['name'], 'guard_name' => 'web'],
                ['group_name' => $perm['group_name'], 'status' => true]
            );
        }
    }
}