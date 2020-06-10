<?php

namespace Foundry\System\Http\Requests\Roles;

use Foundry\Core\Requests\FoundryFormRequest;

class BrowseRolesRequest extends FoundryFormRequest
{
	/**
     * Determine if the role is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return ($this->user() && $this->user()->can('system.roles.read'));
    }


}
