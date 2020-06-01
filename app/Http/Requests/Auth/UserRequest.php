<?php

namespace Foundry\System\Http\Requests\Auth;

use Foundry\Core\Requests\Contracts\EntityRequestInterface;
use Foundry\Core\Requests\FoundryFormRequest;
use Foundry\Core\Requests\Traits\HasEntity;
use Foundry\System\Models\User;

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
		return $this->user();
	}


}
