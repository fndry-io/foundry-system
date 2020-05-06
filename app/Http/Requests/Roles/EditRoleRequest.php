<?php

namespace Foundry\System\Http\Requests\Roles;

class EditRoleRequest extends RoleRequest
{
	/**
	 * Determine if the role is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
        return ($this->user() && $this->user()->can('manage roles'));
	}

}
