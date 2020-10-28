<?php

namespace Foundry\System\Http\Requests\PickLists;

use Foundry\Core\Requests\FoundryFormRequest;

class AddPickListRequest extends FoundryFormRequest
{
	/**
	 * @return bool
	 */
	public function authorize(): bool
	{
        return ($this->user() && $this->user()->can('system.pick_lists.manage'));
	}
}
