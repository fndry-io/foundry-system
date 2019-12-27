<?php

namespace Foundry\System\Http\Requests\Auth;

use Foundry\Core\Requests\Response;
use Foundry\System\Services\UserService;

class SyncUserSettingsRequest extends UserRequest
{

	public static function name(): String {
		return 'foundry.system.auth.sync-settings';
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
	 * @return array
	 */
	public function rules() {
		return [
			'settings' => 'required'
		];
	}

	/**
	 * Handle the request
	 *
	 * @return Response
	 */
	public function handle() : Response
	{
		return UserService::service()->syncSettings($this->user(), $this->input('settings'));
	}

}
