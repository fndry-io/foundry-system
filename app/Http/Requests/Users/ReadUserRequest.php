<?php

namespace Foundry\System\Http\Requests\Users;

class ReadUserRequest extends UserRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
        return ($this->user() && ($this->user()->can('system.users.manage') || $this->user()->can('system.users.read')));
	}
}
