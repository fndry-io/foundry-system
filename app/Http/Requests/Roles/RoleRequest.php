<?php

namespace Foundry\System\Http\Requests\Roles;

use Foundry\Core\Requests\Contracts\EntityRequestInterface;
use Foundry\Core\Requests\FormRequest;
use Foundry\Core\Requests\Traits\HasEntity;
use Foundry\System\Entities\Role;
use LaravelDoctrine\ORM\Facades\EntityManager;

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
		return EntityManager::getRepository(Role::class)->find($id);
	}

}
