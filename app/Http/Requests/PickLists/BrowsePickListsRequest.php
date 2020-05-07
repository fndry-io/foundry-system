<?php

namespace Foundry\System\Http\Requests\PickLists;

use Foundry\Core\Inputs\Types\FormType;
use Foundry\Core\Inputs\Types\RowType;
use Foundry\Core\Inputs\Types\SubmitButtonType;
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
        return ($this->user() && $this->user()->can('read pick lists'));
	}

	/**
	 * @return FormType
	 */
    public function view() : FormType
    {
        $form = $this->form();

        $form->setTitle(__('Pick Lists'));
        $form->setButtons((new SubmitButtonType(__('Filter'), $form->getAction())));
        $form->addChildren(
            RowType::withChildren($form->get('search'))
        );
        return $form;
    }

}
