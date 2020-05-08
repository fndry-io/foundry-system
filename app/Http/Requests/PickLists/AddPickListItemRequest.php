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
        return ($this->user() && $this->user()->can('manage pick lists')) && $this->getEntity()->is_tag;
    }
}
