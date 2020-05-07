<?php

namespace Foundry\System\Http\Requests\PickLists;

class ReadPickListRequest extends PickListRequest
{
	/**
	 * Determine if the board is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return !!($this->user());
	}
}
