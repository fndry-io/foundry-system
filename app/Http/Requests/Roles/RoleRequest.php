<?php

namespace Foundry\System\Http\Requests\Roles;

use Foundry\Core\Requests\Contracts\EntityRequestInterface;
use Foundry\Core\Requests\FormRequest;
use Foundry\Core\Requests\Traits\HasEntity;
use Foundry\System\Models\Role;
use Foundry\System\Repositories\RoleRepository;

abstract class RoleRequest extends FormRequest implements EntityRequestInterface
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
