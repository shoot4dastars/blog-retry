<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;

class RolePermissionController extends Controller
{
    /**
     * Show permissions matrix for all roles.
     * GET /admin/roles/permissions
     */
    public function index()
    {
        $roles = Role::where('name','user')->with('permissions')->get();
        $permissions = Permission::orderBy('name')->get();

        return view('admin.permissions.roles', compact('roles', 'permissions'));
    }

    /**
     * Sync permissions for a given role.
     * PUT /admin/roles/{role}/permissions
     */
    public function update(Request $request, Role $role)
    {
        if ($role->name === 'admin') {
            return back()->with('error', 'Permissions for the admin role cannot be modified.');
        }

        $validated = $request->validate([
            'permissions'   => ['nullable', 'array'],
            'permissions.*' => ['integer', 'exists:permissions,id'],
        ]);

        $role->permissions()->sync($validated['permissions'] ?? []);

        return back()->with('success', "Permissions for role \"{$role->name}\" updated.");
    }
}
