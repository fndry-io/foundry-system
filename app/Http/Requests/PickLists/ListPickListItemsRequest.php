<?php

namespace Foundry\System\Http\Requests\PickLists;

class ListPickListItemsRequest extends PickListRequest
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
