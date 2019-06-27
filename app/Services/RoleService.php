<?php

namespace Foundry\System\Services;

use Foundry\Core\Entities\Contracts\EntityInterface;
use Foundry\Core\Inputs\Inputs;
use Foundry\Core\Requests\Response;
use Foundry\Core\Services\BaseService;
use Foundry\Core\Services\Traits\HasRepository;
use Foundry\System\Entities\Role;
use Foundry\System\Inputs\Role\RoleInput;
use Foundry\System\Repositories\RoleRepository;

class RoleService extends BaseService {

	use HasRepository;

	public function __construct(RoleRepository $repository) {
		$this->setRepository($repository);
	}

	/**
	 * @param RoleInput|Inputs $input
	 *
	 * @return Response
	 */
	public function add(RoleInput $input) : Response
	{
		$role = new Role($input->inputs());
		$this->repository->save($role);
		return Response::success($role);
	}

	/**
	 * @param RoleInput|Inputs $input
	 * @param Role|EntityInterface $role
	 *
	 * @return Response
	 */
	public function edit(RoleInput $input, Role $role) : Response
	{
		$role->fill($input);
		$this->repository->save($role);
		return Response::success($role);
	}

	/**
	 * Delete a user
	 *
	 * @param Role|EntityInterface $role
	 *
	 * @return Response
	 */
	public function delete(Role $role) : Response
	{
		$this->repository->delete($role);
		return Response::success();
	}

}