<?php

namespace Foundry\System\Http\Requests\Users;

use Foundry\System\Models\User;
use Foundry\System\Repositories\UserRepository;

class DeleteUserRequest extends UserRequest
{
	/**
	 * @param mixed $id
	 *
	 * @return null|User|object
	 */
	public function findEntity($id)
	{
		return UserRepository::repository()->query()->withTrashed()->find($id);
	}

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
        return ($this->user() && $this->user()->can('delete users'));
	}
}
