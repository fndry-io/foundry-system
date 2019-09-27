<?php

namespace Foundry\System\Http\Requests\Users;

use Foundry\Core\Requests\Response;
use Foundry\System\Models\User;
use Foundry\System\Repositories\UserRepository;
use Foundry\System\Services\UserService;

class RestoreUserRequest extends UserRequest
{
	public static function name(): String {
		return 'foundry.system.users.restore';
	}

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
		return !!($this->user());
	}

	/**
	 * Handle the request
	 *
	 * @return Response
	 * @throws \Exception
	 */
	public function handle() : Response
	{
		return UserService::service()->restore($this->getEntity());
	}

}
