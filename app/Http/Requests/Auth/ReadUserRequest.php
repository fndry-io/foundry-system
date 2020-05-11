<?php

namespace Foundry\System\Http\Requests\Auth;

use Foundry\Core\Requests\FoundryFormRequest;

class ReadUserRequest extends FoundryFormRequest
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
