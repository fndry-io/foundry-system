<?php

namespace Foundry\System\Http\Requests\Users;

use Foundry\Core\Inputs\Types\FormType;
use Foundry\Core\Inputs\Types\RowType;
use Foundry\Core\Inputs\Types\SectionType;
use Foundry\Core\Inputs\Types\SubmitButtonType;
use Foundry\Core\Requests\FoundryFormRequest;

class BulkAddUserRequest extends FoundryFormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return ($this->user() && $this->user()->isSuperAdmin());
	}

	/**
	 * Make a viewable DocType for the request
	 *
	 * @return FormType
	 */
	public function view() : FormType
	{
		$form = $this->form();

		$form->setTitle(__('Bulk Import'));
		$form->setButtons((new SubmitButtonType(__('Import'), $form->getAction())));

		$form->addChildren(
			(new SectionType(__('Job Title & Position')))->addChildren(
				RowType::withChildren($form->get('bulk'))
			)
		);

		return $form;
	}
}
