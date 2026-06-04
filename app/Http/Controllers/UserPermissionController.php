<?php
namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserPermissionController extends Controller
{
    public function index()
    {
        $users = User::whereDoesntHave('roles', function ($query) {
            $query->where('name', 'admin');
        })->with('roles', 'permissions')->paginate(20);
        return view('admin.permissions.users-list', compact('users'));
    }

    /**
     * Show per-user permission management.
     * GET /admin/users/{user}/permissions
     */
    public function edit(User $user)
    {
        if ($user->isAdmin()) {
            return redirect()
                ->route('admin.users.permissions.index')
                ->with('error', 'Permissions for admin users cannot be modified.');
        }

        $permissions = Permission::orderBy('name')->get();
        $rolePerms = $user->roles->flatMap->permissions->pluck('id')->unique();
        $directPerms = $user->directPermissions()->pluck('id');
        $deniedPerms = $user->deniedPermissions()->pluck('id');

        return view('admin.permissions.user', compact('user', 'permissions', 'rolePerms', 'directPerms', 'deniedPerms'));
    }

    /**
     * Sync direct permissions for a user.
     * PUT /admin/users/{user}/permissions
     */
    public function update(Request $request, User $user)
    {
        if ($user->isAdmin()) {
            return back()->with('error', 'Permissions for admin users cannot be modified.');
        }

        $validated = $request->validate([
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['integer', 'exists:permissions,id'],
            'denied_permissions' => ['nullable', 'array'],
            'denied_permissions.*' => ['integer', 'exists:permissions,id'],
        ]);

        $newGrants = collect($validated['permissions'] ?? []);
        $newDenies = collect($validated['denied_permissions'] ?? []);

        $currentRecords = DB::table('permission_user')
            ->where('user_id', $user->id)
            ->get()
            ->keyBy('permission_id');

        foreach ($newGrants as $permId) {
            if ($currentRecords->has($permId)) {
                DB::table('permission_user')
                    ->where('user_id', $user->id)
                    ->where('permission_id', $permId)
                    ->update(['type' => 'grant']);
            } else {
                DB::table('permission_user')->insert([
                    'user_id' => $user->id,
                    'permission_id' => $permId,
                    'type' => 'grant',
                ]);
            }
        }

        foreach ($newDenies as $permId) {
            if ($currentRecords->has($permId)) {
                DB::table('permission_user')
                    ->where('user_id', $user->id)
                    ->where('permission_id', $permId)
                    ->update(['type' => 'deny']);
            } else {
                DB::table('permission_user')->insert([
                    'user_id' => $user->id,
                    'permission_id' => $permId,
                    'type' => 'deny',
                ]);
            }
        }

        $allSelected = $newGrants->merge($newDenies)->unique();
        DB::table('permission_user')
            ->where('user_id', $user->id)
            ->whereNotIn('permission_id', $allSelected)
            ->delete();

        return back()->with('success', "Permissions for \"{$user->name}\" updated.");
    }
}
