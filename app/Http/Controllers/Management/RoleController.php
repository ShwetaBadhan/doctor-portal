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
        // Return the view path matching your folder structure
        return view('pages.user-management.roles', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::all();
        return view('pages.user-management.roles.create', compact('permissions'));
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

    public function edit(Role $role)
    {
        $permissions = Permission::all();
        $rolePermissions = $role->permissions->pluck('id')->toArray();
        return view('pages.user-management.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id,
            'status' => 'required|boolean',
        ]);

        $role->update([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return redirect()->route('roles.index')->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        // Prevent deleting critical roles
        if ($role->name === 'admin' || $role->name === 'super-admin') {
            return back()->with('error', 'Cannot delete system role.');
        }

        $role->delete();
        return redirect()->route('roles.index')->with('success', 'Role deleted successfully.');
    }

public function managePermissions(Role $role)
{
    // Get active permissions grouped by group_name
    $permissions = Permission::where('status', true)
        ->orderBy('group_name')
        ->orderBy('name')
        ->get()
        ->groupBy('group_name');

    // Current role's assigned permission names
    $rolePermissions = $role->permissions->pluck('name')->toArray();

    return view('pages.user-management.assign-permissions', compact('role', 'permissions', 'rolePermissions'));
}

public function assignPermissions(Request $request, Role $role)
{
    $request->validate([
        'permissions' => 'sometimes|array',
    ]);

    // Sync permissions by NAME (Spatie best practice)
    $role->syncPermissions($request->permissions ?? []);

    return redirect()->route('roles.index')->with('success', 'Permissions updated successfully!');
}
}
