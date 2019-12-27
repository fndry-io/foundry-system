<?php

namespace Foundry\System\Http\Requests\Auth;

use Foundry\Core\Requests\FormRequest;
use Foundry\Core\Requests\Response;
use Foundry\System\Services\UserService;
use Illuminate\Support\Facades\Auth;

class ReadUserRequest extends FormRequest
{

	public static function name(): String {
		return 'foundry.system.auth.user';
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
	 */
    public function handle() : Response
    {
        return UserService::service()->returnGuardUser(Auth::guard(), false);
    }

}
