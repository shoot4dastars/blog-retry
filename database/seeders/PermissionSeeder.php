<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Permission list.
     * 'name' is the slug used in code; 'route_name' is optional for UI display.
     */
    private array $permissions = [
        // Posts
        ['name' => 'create-posts',  'route_name' => 'posts.create'],
        ['name' => 'edit-posts',    'route_name' => 'posts.edit'],
        ['name' => 'delete-posts',  'route_name' => 'posts.destroy'],
        ['name' => 'publish-posts', 'route_name' => 'posts.approve'],
        ['name' => 'view-drafts',   'route_name' => 'posts.drafts'],
        ['name' => 'view-pending',  'route_name' => 'posts.pending'],

        // Comments
        ['name' => 'create-comments', 'route_name' => 'comments.store'],
        ['name' => 'edit-comments',   'route_name' => 'comments.update'],
        ['name' => 'delete-comments', 'route_name' => 'comments.destroy'],

        // Admin
        ['name' => 'manage-users',       'route_name' => 'admin.users'],
        ['name' => 'manage-permissions', 'route_name' => 'admin.roles.permissions.index'],
    ];

    /** Default permissions per role */
    private array $rolePermissions = [
        'admin' => [
            'create-posts', 'edit-posts', 'delete-posts', 'publish-posts',
            'view-drafts', 'view-pending',
            'create-comments', 'edit-comments', 'delete-comments',
            'manage-users', 'manage-permissions',
        ],
        'user' => [
            'create-posts', 'edit-posts', 'delete-posts',
            'view-drafts',
            'create-comments', 'edit-comments', 'delete-comments',
        ],
    ];

    public function run(): void
    {
        // Create permissions
        foreach ($this->permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm['name']], $perm);
        }

        // Assign to roles
        foreach ($this->rolePermissions as $roleName => $permNames) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            $ids  = Permission::whereIn('name', $permNames)->pluck('id');
            $role->permissions()->sync($ids);
        }
    }
}
