<?php

namespace Foundry\System\Http\Requests\Roles;

use Foundry\Core\Requests\Contracts\EntityRequestInterface;
use Foundry\Core\Requests\FoundryFormRequest;
use Foundry\Core\Requests\Traits\HasEntity;
use Foundry\Core\Entities\Contracts\IsRole;
use Foundry\System\Models\Role;
use Foundry\System\Repositories\RoleRepository;

/**
 * Class RoleRequest
 *
 * @method IsRole getEntity()
 *
 * @package Foundry\System\Http\Requests\Roles
 */
abstract class RoleRequest extends FoundryFormRequest implements EntityRequestInterface
{
	use HasEntity;

	/**
	 * @param mixed $id
	 *
	 * @return null|Role|object
	 */
	public function findEntity($id)
	{
		return RoleRepository::repository()->find($id);
	}

}
