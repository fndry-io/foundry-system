<?php

namespace Foundry\System\Http\Requests\PickLists;

use Foundry\Core\Inputs\Types\FormType;
use Foundry\Core\Inputs\Types\RowType;
use Foundry\Core\Inputs\Types\SectionType;
use Foundry\Core\Inputs\Types\SubmitButtonType;
use Foundry\Core\Requests\Contracts\EntityRequestInterface;
use Foundry\Core\Requests\Contracts\InputInterface;
use Foundry\Core\Requests\Contracts\ViewableFormRequestInterface;
use Foundry\Core\Requests\Response;
use Foundry\Core\Requests\Traits\HasInput;
use Foundry\System\Inputs\PickList\PickListInput;
use Foundry\System\Services\PickListService;
use Modules\Foundry\Checklists\Inputs\Checklist\ChecklistInput;

class EditPickListRequest extends PickListRequest implements ViewableFormRequestInterface, EntityRequestInterface, InputInterface
{
	use HasInput;

	public static function name(): String {
		return 'foundry.system.pick-lists.edit';
	}

	/**
	 * @param $inputs
	 *
	 * @return \Foundry\Core\Inputs\Inputs|ChecklistInput
	 */
	public function makeInput($inputs) {
		return new PickListInput($inputs);
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
		return PickListService::service()->edit($this->input, $this->getEntity());
	}

	/**
	 * Make a viewable DocType for the request
	 *
	 * @return FormType
	 */
	public function view() : FormType
	{
		$form = $this->form();

		$form->setTitle(__('Update Pick List'));
		$form->setButtons((new SubmitButtonType(__('Update'), $form->getAction())));

		$item = (new SectionType(__('Details')))->addChildren(
            RowType::withChildren($form->get('label')),
            RowType::withChildren($form->get('description'))
		);

		$form->addChildren(
			$item
		);

		return $form;
	}

}
