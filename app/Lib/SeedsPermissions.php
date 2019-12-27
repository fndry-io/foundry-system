<?php


namespace Foundry\System\Lib;


use Foundry\System\Models\Permission;
use Foundry\System\Repositories\PermissionRepository;

trait SeedsPermissions
{

    protected function seedPermissions($module, $permissions)
    {
        $repository = PermissionRepository::repository();
        foreach ($permissions as $guard => $groups) {
            Permission::seed($module, $guard, $groups);
        }
    }
}
