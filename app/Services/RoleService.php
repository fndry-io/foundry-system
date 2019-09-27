<?php

namespace Foundry\System\Services;

use Foundry\Core\Inputs\Inputs;
use Foundry\Core\Requests\Response;
use Foundry\Core\Services\BaseService;
use Foundry\System\Models\Role;
use Foundry\System\Inputs\Role\RoleInput;
use Foundry\System\Repositories\RoleRepository;
use Illuminate\Database\Eloquent\Builder;

class RoleService extends BaseService {

	/**
	 * Browse Users
	 *
	 * @param Inputs $inputs
	 * @param int $page
	 * @param int $perPage
	 *
	 * @return Response
	 */
	public function browse( Inputs $inputs, $page = 1, $perPage = 20 ): Response {
		return Response::success(RoleRepository::repository()->filter(function(Builder $qb) use ($inputs) {

			$qb->select('id', 'name')
				->orderBy('name', 'ASC');

			return $qb;

		}, $page, $perPage));
	}

	/**
	 * @param RoleInput|Inputs $input
	 *
	 * @return Response
	 */
	public function add(RoleInput $input) : Response
	{
		$role = new Role($input->values());
		RoleRepository::repository()->save($role);
		return Response::success($role);
	}

	/**
	 * @param RoleInput|Inputs $input
	 * @param Role $role
	 *
	 * @return Response
	 */
	public function edit(RoleInput $input, Role $role) : Response
	{
		$role->fill($input->values());
		RoleRepository::repository()->save($role);
		return Response::success($role);
	}

	/**
	 * Delete a user
	 *
	 * @param Role $role
	 *
	 * @return Response
	 * @throws \Exception
	 */
	public function delete(Role $role) : Response
	{
		RoleRepository::repository()->delete($role);
		return Response::success();
	}

}