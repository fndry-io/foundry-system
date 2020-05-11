<?php

namespace Foundry\System\Http\Requests\Auth;

class EditUserRequest extends UserRequest
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
}
