<?php

namespace App\Traits;

use App\Models\Permission;
use Illuminate\Support\Facades\DB;

trait HasPermissions
{
    /**
     * Check if this model has a specific permission directly.
     */
    public function hasDirectPermission(string $permission): bool
    {
        return $this->directPermissions()->where('name', $permission)->exists();
    }

    /**
     * Check if this model has a specific permission explicitly denied.
     */
    public function hasDeniedPermission(string $permission): bool
    {
        return $this->deniedPermissions()->where('name', $permission)->exists();
    }

    /**
     * Check if user is admin (bypass all permission checks)
     */
    public function isAdmin(): bool
    {
        if (method_exists($this, 'hasRole')) {
            return $this->hasRole('admin');
        }
        return false;
    }

    /**
     * For Role: check direct permission only.
     * For User: check direct OR via roles.
     */
    public function hasPermissionTo(string $permission): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        if ($this->hasDeniedPermission($permission)) {
            return false;
        }

        if ($this->hasDirectPermission($permission)) {
            return true;
        }

        if (method_exists($this, 'roles')) {
            if (!$this->relationLoaded('roles')) {
                $this->load('roles.permissions');
            }
            foreach ($this->roles as $role) {
                if ($role->permissions->contains('name', $permission)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Get directly granted permissions (not from roles)
     */
    public function directPermissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_user', 'user_id', 'permission_id')
            ->wherePivot('type', 'grant');
    }

    /**
     * Get explicitly denied permissions
     */
    public function deniedPermissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_user', 'user_id', 'permission_id')
            ->wherePivot('type', 'deny');
    }
}
