<?php

namespace Foundry\System\Http\Requests\Auth;

use Foundry\Core\Entities\Contracts\EntityInterface;
use Foundry\Core\Requests\Contracts\ViewableFormRequestInterface;
use Foundry\Core\Requests\FormRequest;
use Foundry\Core\Requests\Response;
use Foundry\System\Entities\Entity;
use Foundry\System\Entities\User;
use Foundry\System\Http\Requests\Traits\WithoutInput;
use Foundry\System\Services\UserService;
use LaravelDoctrine\ORM\Facades\EntityManager;

class LogoutRequest extends FormRequest implements ViewableFormRequestInterface
{
	use WithoutInput;

	public static function name(): String {
		return 'foundry.system.auth.logout';
	}

	/**
	 * @param mixed $id
	 *
	 * @return EntityInterface|Entity|null|object|User
	 */
	public function getEntity($id)
	{
		return EntityManager::getRepository(User::class)->find($id);
	}

	/**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return !!(auth_user());
    }

	/**
	 * Handle the request
	 *
	 * @return Response
	 */
    public function handle() : Response
    {
        return UserService::service()->logout();
    }


}
