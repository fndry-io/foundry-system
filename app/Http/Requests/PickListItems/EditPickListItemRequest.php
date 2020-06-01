<?php

namespace Foundry\System\Http\Requests\PickListItems;

use Foundry\Core\Inputs\Types\FormType;
use Foundry\Core\Inputs\Types\RowType;
use Foundry\Core\Inputs\Types\SectionType;
use Foundry\Core\Inputs\Types\SubmitButtonType;
use Foundry\Core\Requests\Contracts\EntityRequestInterface;
use Foundry\Core\Requests\Contracts\InputInterface;
use Foundry\Core\Requests\Contracts\ViewableFormRequestInterface;
use Foundry\Core\Requests\Response;
use Foundry\Core\Requests\Traits\HasInput;
use Foundry\System\Inputs\PickListItem\PickListEditItemInput;
use Foundry\System\Services\PickListItemService;

class EditPickListItemRequest extends PickListItemRequest implements EntityRequestInterface
{

	/**
	 * Determine if the checklist is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
        return ($this->user() && $this->user()->can('edit pick list items'));
	}




}
