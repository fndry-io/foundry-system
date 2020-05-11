<?php

namespace Foundry\System\Http\Requests\Permissions;

use Foundry\Core\Requests\FoundryFormRequest;

class SyncPermissionsRequest extends FoundryFormRequest
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
