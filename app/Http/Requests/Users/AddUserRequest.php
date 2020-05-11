<?php

namespace Foundry\System\Http\Requests\Users;

use Foundry\Core\Requests\FoundryFormRequest;

class AddUserRequest extends FoundryFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return ($this->user() && $this->user()->can('manage users'));
    }
}
