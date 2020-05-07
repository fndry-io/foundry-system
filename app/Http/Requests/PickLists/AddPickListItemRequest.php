<?php

namespace Foundry\System\Http\Requests\PickLists;

use Foundry\Core\Inputs\SimpleInputs;
use Foundry\Core\Inputs\Types\FormType;
use Foundry\Core\Inputs\Types\RowType;
use Foundry\Core\Inputs\Types\SectionType;
use Foundry\Core\Inputs\Types\SubmitButtonType;
use Foundry\Core\Inputs\Types\TextInputType;
use Foundry\Core\Requests\Contracts\InputInterface;
use Foundry\Core\Requests\Contracts\ViewableFormRequestInterface;
use Foundry\Core\Requests\Response;
use Foundry\Core\Requests\Traits\HasInput;
use Foundry\Core\Support\InputTypeCollection;
use Foundry\System\Inputs\PickListItem\PickListItemInput;
use Foundry\System\Services\PickListItemService;

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

	/**
	 * Make a viewable DocType for the request
	 *
	 * @return FormType
	 */
	public function view() : FormType
	{
		$form = $this->form();

		$form->setValue('label', '');

		$form->setTitle(__('Add Tag'));
		$form->setButtons((new SubmitButtonType(__('Add'), $form->getAction())));

		$item = (new SectionType(__('Pick List Tag')))->addChildren(
			RowType::withChildren($form->get('label')->setValue(''))
		);

		$form->addChildren(
			$item
		);

		return $form;
	}

}
