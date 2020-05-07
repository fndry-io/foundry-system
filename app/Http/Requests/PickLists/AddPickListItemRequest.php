<?php

namespace Foundry\System\Http\Requests\PickLists;

class AddPickListItemRequest extends PickListRequest
{
	/**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
    	//todo update to use the permissions
        return ($this->user() && $this->user()->can('create pick list items')) && $this->getEntity()->is_tag;
    }
}
