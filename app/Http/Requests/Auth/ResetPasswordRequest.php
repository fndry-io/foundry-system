<?php

namespace Foundry\System\Http\Requests\Auth;

use Foundry\Core\Requests\FoundryFormRequest;

class ResetPasswordRequest extends FoundryFormRequest
{
	/**
	 * @param mixed $id
	 *
	 * @return null|object|User
	 */
	public function getEntity($id)
	{
		return null;
	}

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}
}
