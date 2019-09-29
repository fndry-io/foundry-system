<?php

namespace Foundry\System\Services;

use Foundry\Core\Entities\Contracts\IsRole;
use Foundry\Core\Inputs\Inputs;
use Foundry\Core\Requests\Response;
use Foundry\Core\Services\BaseService;
use Foundry\System\Inputs\Role\RoleInput;
use Foundry\System\Repositories\RoleRepository;

class RoleService extends BaseService
{

	/**
	 * @param Inputs $inputs
	 * @param int $page
	 * @param int $perPage
	 *
	 * @return Response The data key will contain an instance of Paginator
	 * @see Paginator
	 */
	public function browse(Inputs $inputs, $page = 1, $perPage = 20): Response
	{
		return Response::success(RoleRepository::repository()->browse($inputs->values(), $page, $perPage));
	}

	/**
	 * @param RoleInput|Inputs $input
	 *
	 * @return Response
	 */
	public function add(RoleInput $input): Response
	{
		$role = RoleRepository::repository()->insert($input->values());
		if ($role) {
			return Response::success($role);
		} else {
			return Response::error(__('Unable to add role'), 500);
		}
	}

	/**
	 * @param RoleInput|Inputs $input
	 * @param IsRole $role
	 *
	 * @return Response
	 */
	public function edit(RoleInput $input, IsRole $role): Response
	{
		$role = RoleRepository::repository()->update($role, $input->values());
		if ($role) {
			return Response::success($role);
		} else {
			return Response::error(__('Unable to edit role'), 500);
		}
	}

	/**
	 * Delete a role
	 *
	 * @param IsRole $role
	 *
	 * @return Response
	 */
	public function delete(IsRole $role): Response
	{
		if (RoleRepository::repository()->delete($role)) {
			return Response::success();
		} else {
			return Response::error(__('Unable to delete role'), 500);
		}
	}


}