<?php

namespace Foundry\System\Http\Requests\PickLists;

class EditPickListRequest extends PickListRequest
{
	/**
	 * Determine if the checklist is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
        return ($this->user() && $this->user()->can('manage pick lists'));
	}
}
