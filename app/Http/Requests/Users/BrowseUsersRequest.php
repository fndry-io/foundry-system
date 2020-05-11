<?php

namespace Foundry\System\Http\Requests\Users;

use Foundry\Core\Requests\FoundryFormRequest;

class BrowseUsersRequest extends FoundryFormRequest
{
	/**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
    	//todo update to use the permissions
        return ($this->user() && $this->user()->can('read users'));
    }
}
