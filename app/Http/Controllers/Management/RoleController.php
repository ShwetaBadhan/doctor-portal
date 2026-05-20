<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index()
{
    $roles = Role::orderBy('created_at', 'desc')->get();
    
    
    $allPermissions = Permission::orderBy('group_name')
        ->orderBy('name')
        ->get()
        ->groupBy('group_name');
    
    return view('pages.user-management.roles', compact('roles', 'allPermissions'));
}

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'status' => 'required|boolean',
        ]);

        $role = Role::create([
            'name' => $request->name,
            'guard_name' => 'web',
            'status' => $request->status,
        ]);

        return redirect()->route('roles.index')->with('success', 'Role created successfully.');
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,'.$role->id,
            'status' => 'required|boolean',
        ]);

        $role->update([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return redirect()->route('roles.index')->with('success', 'Role updated successfully!');
    }

    public function destroy(Role $role)
    {
        if (in_array(strtolower($role->name), ['admin', 'super-admin', 'owner'])) {
            return back()->with('error', 'Cannot delete system role.');
        }

        $role->delete();
        return redirect()->route('roles.index')->with('success', 'Role deleted successfully.');
    }

    // ✅ Ye method permissions modal ke liye data return karega
    public function getPermissionsModal(Role $role)
    {
        $allPermissions = Permission::orderBy('group_name')
            ->orderBy('name')
            ->get()
            ->groupBy('group_name');
        
        $rolePermissionNames = $role->permissions->pluck('name')->toArray();
        
        return view('pages.user-management.role-permissions-modal', compact('role', 'allPermissions', 'rolePermissionNames'));
    }

    // ✅ Ye method permissions update karega
    public function updatePermissions(Request $request, Role $role)
    {
        $request->validate([
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name'
        ]);

        $role->syncPermissions($request->permissions ?? []);

        return redirect()->route('roles.index')
            ->with('success', 'Permissions updated successfully for '.$role->name.'!');
    }
}