<?php

namespace Foundry\System\Http\Requests\Users;

class EditUserRequest extends UserRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
        return ($this->user() && $this->user()->can('edit users'));
	}
}
