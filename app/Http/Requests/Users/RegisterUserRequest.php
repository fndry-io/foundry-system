<?php

namespace Foundry\System\Http\Requests\Users;

use Foundry\Core\Requests\FoundryFormRequest;

class RegisterUserRequest extends FoundryFormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
	    //todo connect this to a setting for allowing public registrations
        return ($this->user() && $this->user()->can('add users'));
	}
}
