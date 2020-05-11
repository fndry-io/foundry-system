<?php

namespace Foundry\System\Http\Requests\Auth;

class SyncUserSettingsRequest extends UserRequest
{
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
}
