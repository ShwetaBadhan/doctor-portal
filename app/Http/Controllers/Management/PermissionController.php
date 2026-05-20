<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Permission;
use Spatie\Permission\Models\Role as SpatieRole;
use Illuminate\Validation\Rule;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::with('roles')->orderBy('name')->get(); 
        $allRoles = SpatieRole::all();
        return view('pages.user-management.permissions', compact('permissions', 'allRoles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name',
            'group_name' => 'nullable|string|max:255',
            'status' => 'required|in:0,1',
            'guard_name' => 'nullable|in:web,api',
        ]);

        Permission::create([
            'name' => $request->name,
            'group_name' => $request->group_name,
            'guard_name' => $request->guard_name ?? 'web',
            'status' => (bool) $request->status,
        ]);

        return redirect()->route('permissions.index')->with('success', 'Permission created successfully!');
    }

    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('permissions')->ignore($permission->id)],
            'group_name' => 'nullable|string|max:255',
            'status' => 'required|in:0,1',
            'guard_name' => 'nullable|in:web,api',
        ]);

        $permission->update([
            'name' => $request->name,
            'group_name' => $request->group_name,
            'guard_name' => $request->guard_name ?? 'web',
            'status' => (bool) $request->status,
        ]);

        // ✅ Roles sync karna (by NAME)
        if ($request->has('roles')) {
            $permission->roles()->sync(
                SpatieRole::whereIn('name', $request->roles)->pluck('id')
            );
        } else {
            $permission->roles()->detach();
        }

        return redirect()->route('permissions.index')->with('success', 'Permission updated successfully!');
    }

    public function destroy(Permission $permission)
    {
        $protected = ['view users', 'create users', 'edit users', 'delete users', 'manage roles'];
        if (in_array(strtolower($permission->name), array_map('strtolower', $protected))) {
            return back()->with('error', 'Cannot delete system permission.');
        }

        $permission->delete();
        return redirect()->route('permissions.index')->with('success', 'Permission deleted successfully!');
    }
}