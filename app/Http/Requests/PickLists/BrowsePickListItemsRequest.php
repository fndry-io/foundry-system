<?php

namespace Foundry\System\Http\Requests\PickLists;

class BrowsePickListItemsRequest extends PickListRequest
{
	/**
	 * Determine if the item is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
        return ($this->user() && $this->user()->can('system.pick_lists.read'));
	}
}
