<?php

namespace Foundry\System\Listeners;

use Foundry\System\Models\Permission;
use Foundry\System\Models\Role;

class SyncPermissions
{
    public function handle()
    {
        $admin_role = config('permission.admin.role');
        if (!Role::query()->where('slug', $admin_role)->exists()) {
            $role = new Role([
                'name' => config('permission.admin.name'),
                'slug' => $admin_role,
                'guard_name' => config('auth.defaults.guard')
            ]);
            $role->save();
        }

        $permissions = [
            'system' => [
                'system' => [
                    'manage system'
                ],
                'users' => [
                    'browse users',
                    'add users',
                    'edit users',
                    'delete users',
                    'archive users',
                    'read users'
                ],
                'activities' => [
                    'browse activities'
                ],
                'files & folders' => [
                    'browse folders',
                    'add folders',
                    'edit folders',
                    'delete folders',
                    'upload files',
                    'edit files',
                    'delete files',
                    'read/download files'
                ],
                'roles & permissions' => [
                    'manage roles',
                    'read roles'
                ],
                'pick lists' => [
                    'browse pick lists',
                    'add pick lists',
                    'edit pick lists',
                    'disable pick lists',
                    'browse pick list items',
                    'add pick list items',
                    'edit pick list items',
                    'disable pick list items'
                ]
            ]
        ];
        foreach ($permissions as $guard => $groups) {
            Permission::seed('foundry/system', $guard, $groups);
        }
    }
}
