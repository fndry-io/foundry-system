<?php

namespace Foundry\System\Http\Requests\Users;

use Foundry\Core\Entities\Contracts\IsUser;
use Foundry\Core\Requests\Contracts\EntityRequestInterface;
use Foundry\Core\Requests\FormRequest;
use Foundry\Core\Requests\FoundryFormRequest;
use Foundry\Core\Requests\Traits\HasEntity;
use Foundry\System\Models\User;
use Foundry\System\Repositories\UserRepository;

/**
 * Class UserRequest
 *
 * @method User getEntity()
 *
 * @package Foundry\System\Http\Requests\Users
 */
abstract class UserRequest extends FoundryFormRequest implements EntityRequestInterface
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
