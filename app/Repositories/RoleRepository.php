<?php

namespace Foundry\System\Repositories;

use Foundry\Core\Repositories\ModelRepository;
use Foundry\System\Models\Role;

class RoleRepository extends ModelRepository {

	/**
	 * Returns the class name of the object managed by the repository.
	 *
	 * @return string|Role
	 */
	public function getClassName()
	{
		return Role::class;
	}
}