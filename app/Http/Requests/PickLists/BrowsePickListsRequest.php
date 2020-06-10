<?php

namespace Foundry\System\Http\Requests\PickLists;

use Foundry\Core\Requests\FoundryFormRequest;

class BrowsePickListsRequest extends FoundryFormRequest
{
	/**
	 * Determine if the item is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
        return ($this->user() && $this->user()->can('system.pick-lists.read'));
	}
}
