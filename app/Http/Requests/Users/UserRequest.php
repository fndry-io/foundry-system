<?php

namespace Foundry\System\Http\Requests\Users;

use Foundry\Core\Requests\Contracts\EntityRequestInterface;
use Foundry\Core\Requests\FormRequest;
use Foundry\Core\Requests\Traits\HasEntity;
use Foundry\System\Models\User;
use Foundry\System\Repositories\UserRepository;

abstract class UserRequest extends FormRequest implements EntityRequestInterface
{
	use HasEntity;

	/**
	 * @param mixed $id
	 *
	 * @return null|User|object
	 */
	public function findEntity($id)
	{
		return UserRepository::repository()->find($id);
	}

}
