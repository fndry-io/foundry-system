<?php

namespace Foundry\System\Repositories;

use Foundry\Core\Models\Model;
use Foundry\Core\Repositories\ModelRepository;
use Foundry\Core\Entities\Contracts\IsPermission;
use Foundry\System\Models\Permission;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class PermissionRepository
 *
 * @method boolean delete(IsPermission | Permission | Model | int $model)
 * @method IsPermission|Permission|Model|boolean insert(array $data)
 * @method IsPermission|Permission|Model|boolean update(IsPermission | Permission | Model | int $model, array $data)
 * @method static IsPermission|Permission|Model make($values)
 * @method IsPermission|Permission|Model getModel(Model $id)
 *
 * @package Modules\Agm\Contacts\Repositories
 */
class PermissionRepository extends ModelRepository
{

	/**
	 * Returns the class name of the object managed by the repository.
	 *
	 * @return string|Permission
	 */
	public function getClassName()
	{
		return Permission::class;
	}

	/**
	 * @param array $inputs
	 * @param int $page
	 * @param int $perPage
	 *
	 * @return Paginator
	 */
	public function browse(array $inputs, $page = 1, $perPage = 20): Paginator
	{
		return $this->filter(function (Builder $query) use ($inputs) {
			$query->select('id', 'name')
			      ->orderBy('name', 'ASC');

			return $query;
		}, $page, $perPage);
	}


}
