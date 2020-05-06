<?php

namespace Foundry\System\Http\Requests\Roles;

class DeleteRoleRequest  extends RoleRequest
{

	public function authorize()
	{
        return ($this->user() && $this->user()->can('manage roles'));
	}

}
