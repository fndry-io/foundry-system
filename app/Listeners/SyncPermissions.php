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
                    'system.manage'
                ],
                'system users' => [
                    'system.users.manage',
                    'system.users.read'
                ],
                'system activities' => [
                    'system.activities.read'
                ],
                'system settings' => [
                    'system.settings.read',
                    'system.settings.manage'
                ],
                'system files' => [
                    'system.files.read',
                    'system.files.create',
                    'system.files.manage'
                ],
                'system folders' => [
                    'system.folders.read',
                    'system.folders.create',
                    'system.folders.manage',
                ],
                'system roles & permissions' => [
                    'system.roles.manage',
                    'system.roles.read'
                ],
                'system pick lists' => [
                    'system.pick_lists.manage',
                    'system.pick_lists.read'
                ]
            ]
        ];
        foreach ($permissions as $guard => $groups) {
            Permission::seed('foundry/system', $guard, $groups);
        }
    }
}
