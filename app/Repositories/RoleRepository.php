<?php

namespace Foundry\System\Repositories;

use Foundry\Core\Entities\Contracts\IsRole;
use Foundry\Core\Models\Model;
use Foundry\Core\Repositories\ModelRepository;
use Foundry\System\Models\Role;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class RoleRepository
 *
 * @method boolean delete(IsRole | Model | int $model)
 * @method IsRole|Model|boolean insert(array $data)
 * @method IsRole|Model|boolean update(IsRole | Model | int $model, array $data)
 * @method static IsRole|Model make($values)
 * @method IsRole|Model findOrAbort(Model $id)
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
			$query->select('id', 'name')
			      ->orderBy('name', 'ASC');

			return $query;
		}, $page, $perPage);
	}
}