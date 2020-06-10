<?php

namespace Foundry\System\Http\Requests\PickListItems;

use Foundry\Core\Requests\Contracts\EntityRequestInterface;

class EditPickListItemRequest extends PickListItemRequest implements EntityRequestInterface
{

	/**
	 * Determine if the checklist is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
        return ($this->user() && $this->user()->can('system.pick-list.manage'));
	}




}
