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
use Foundry\System\Inputs\PickListItem\PickListItemInput;
use Foundry\System\Inputs\SearchFilterInput;
use Foundry\System\Services\PickListItemService;
use Foundry\System\Services\PickListService;
use Foundry\System\Inputs\PickList\PickListInput;


class EditPickListItemRequest extends PickListItemRequest implements ViewableFormRequestInterface, EntityRequestInterface, InputInterface
{
	use HasInput;

	public static function name(): String {
		return 'picklistitems.picklistitems.edit';
	}

	/**
	 * @param $inputs
	 *
	 * @return \Foundry\Core\Inputs\Inputs|ChecklistInput
	 */
	public function makeInput($inputs) {
		return new PickListEditItemInput($inputs);
	}

	/**
	 * Determine if the checklist is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return !!($this->user());
	}

	/**
	 * Handle the request
	 *
	 * @return Response
	 */
	public function handle() : Response
	{
		return PickListItemService::service()->edit($this->input, $this->getEntity());
	}

	/**
	 * Make a viewable DocType for the request
	 *
	 * @return FormType
	 */
	public function view() : FormType
	{
		$form = $this->form();

		$form->setTitle(__('Update Picklist Item'));
		$form->setButtons((new SubmitButtonType(__('Update'), $form->getAction())));

		$picklist = (new SectionType(__('Details')))->addChildren(
            RowType::withChildren($form->get('name')),
            RowType::withChildren($form->get('description')),
            RowType::withChildren($form->get('status'))

		);

		$form->addChildren(
			$picklist
		);

		return $form;
	}

}
