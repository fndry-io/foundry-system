<?php

namespace Foundry\System\Services;

use Foundry\Core\Entities\Contracts\EntityInterface;
use Foundry\Core\Inputs\Inputs;
use Foundry\Core\Requests\Response;
use Foundry\System\Entities\Role;
use Foundry\System\Inputs\Role\RoleInput;
use Foundry\System\Repositories\RoleRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class RoleService {

	protected $repository;

	protected $per_page = 20;

	public function __construct(RoleRepository $repository) {
		$this->repository = $repository;
	}

	/**
	 * @return Role[]|LengthAwarePaginator
	 */
	public function all() : LengthAwarePaginator
	{
		$this->repository->paginateAll($this->per_page);
	}

	/**
	 * @param $id
	 *
	 * @return null|object|User
	 */
	public function find($id)
	{
		return $this->repository->find($id);
	}

	/**
	 * @param \Closure $builder
	 *
	 * @return Role[]|LengthAwarePaginator
	 */
	public function filter(\Closure $builder = null) : LengthAwarePaginator
	{
		return $this->repository->filter($builder, $this->per_page);
	}

	/**
	 * @param RoleInput|Inputs $input
	 *
	 * @return Response
	 */
	public function create(RoleInput $input) : Response
	{
		$user = new Role($input->inputs());
		$this->repository->save($user);
		return Response::success($user);
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

	/**
	 * @return static
	 */
	static function service()
	{
		return app(static::class);
	}

}