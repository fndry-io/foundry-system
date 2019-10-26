<?php

namespace Foundry\System\Repositories;

use Foundry\Core\Entities\Contracts\IsRole;
use Foundry\Core\Models\Model;
use Foundry\Core\Repositories\ModelRepository;
use Foundry\System\Inputs\Role\Types\Guard;
use Foundry\System\Models\Permission;
use Foundry\System\Models\Role;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class RoleRepository
 *
 * @method IsRole|Role find($id)
 * @method boolean delete(IsRole | Role | int $model)
 * @method IsRole|Role|boolean insert(array $data)
 * @method IsRole|Role|boolean update(IsRole | Role | int $model, array $data)
 * @method static IsRole|Role make($values)
 * @method Role|Role getModel(Model $id)
 *
 * @package Modules\Agm\Contacts\Repositories
 */
class RoleRepository extends ModelRepository
{

	/**
	 * Returns the class name of the object managed by the repository.
	 *
	 * @return string|Role
	 */
	public function getClassName()
	{
		return Role::class;
	}

	/**
	 * @param array $inputs
	 * @param int $page
	 * @param int $perPage
	 *
	 * @return \Illuminate\Contracts\Pagination\Paginator
	 */
	public function browse(array $inputs, $page = 1, $perPage = 20): Paginator
	{
		return $this->filter(function (Builder $query) use ($inputs) {
			$query->select('id', 'name', 'guard_name')
			      ->orderBy('name', 'ASC');

			return $query;
		}, $page, $perPage);
	}

    /**
     * @param Role|int $role
     * @param $permissionIds
     * @return bool
     * @throws \Exception
     */
	public function syncRolePermissions($role, $permissionIds)
    {
        $role = $this->getModel($role);
        if ($role->syncPermissions($permissionIds)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param array $rolesWithPermissions
     * @return bool
     * @throws \Exception
     */
    public function syncPermissions($rolesWithPermissions)
    {
        foreach ($rolesWithPermissions as $id => $permissionIds) {
            if (!$this->syncRolePermissions($id, $permissionIds)) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param IsRole|Role|Model|int $role
     * @param int $page
     * @param int $perPage
     *
     * @return Paginator
     * @throws \Exception
     */
    public function permissions($role, $page = 1, $perPage = 20): Paginator
    {
        $role = $this->getModel($role);
        return $role->permissions()->paginate($perPage, ['*'], 'page', $page);
    }

    /**
     * Gets the roles, permissions and what roles have what permissions
     *
     * @param $guard
     * @param null $role
     * @return array
     * @throws \Exception|NotFoundHttpException
     */
    public function permissionsWithRolesForGuard($guard, $role = null)
    {
        $guards = Guard::options();

        $rules = new Collection($guards);
        if (!in_array($guard, $rules->pluck('value')->toArray())) {
            throw new NotFoundHttpException(__('Guard not found'));
        }

        if ($role) {
            $role = $this->getModel($role);
        }
        $query = Role::query()->where('guard_name', $guard)->orderBy('name', 'ASC');
        if ($role) {
            $query->where('id', $role->getKey());
        }
        $roles = $query->get();

        $permissions = Permission::query()->where('guard_name', $guard)->orderBy('name', 'ASC')->get();

        $assigned = [];
        foreach ($roles as $role) {
            $assigned[$role->getKey()] = $role->permissions->pluck('id')->toArray();
        }

        $admin = config('permission.admin');

        return compact('roles', 'permissions', 'assigned', 'guards', 'guard', 'admin');
    }
}
