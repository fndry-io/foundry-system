<?php

namespace Foundry\System\Http\Requests\Users;

use Foundry\Core\Requests\Response;
use Foundry\System\Services\UserService;

class DeleteUserRequest extends UserRequest
{

	public static function name(): String {
		return 'foundry.system.users.delete';
	}

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
		return (auth_user());
	}

	/**
	 * Handle the request
	 *
	 * @return Response
	 */
	public function handle() : Response
	{
		return UserService::service()->delete($this->getEntity());
	}

}
