<?php

namespace Foundry\System\Http\Requests\PickLists;

use Foundry\Core\Inputs\Types\FormType;
use Foundry\Core\Inputs\Types\RowType;
use Foundry\Core\Inputs\Types\SectionType;
use Foundry\Core\Inputs\Types\SubmitButtonType;
use Foundry\Core\Requests\Contracts\InputInterface;
use Foundry\Core\Requests\Contracts\ViewableFormRequestInterface;
use Foundry\Core\Requests\FormRequest;
use Foundry\Core\Requests\Response;
use Foundry\Core\Requests\Traits\HasInput;
use Foundry\System\Inputs\PickList\PickListInput;
use Foundry\System\Services\PickListService;

class AddPickListRequest extends FormRequest implements InputInterface, ViewableFormRequestInterface
{
	use HasInput;

	public static function name(): String {
		return 'foundry.system.pick-lists.add';
	}

	/**
	 * @param $inputs
	 *
	 * @return string
	 */
	public function makeInput($inputs) {
		return new PickListInput($inputs);
	}

	/**
	 * @return bool
	 */
	public function authorize(): bool
	{
        return ($this->user() && $this->user()->can('create pick lists'));
	}

	/**
	 * Handle the request
	 *
	 * @return Response
	 */
	public function handle() : Response
	{
		return PickListService::service()->add($this->input);
	}

	/**
	 * Make a viewable DocType for the request
	 *
	 * @return FormType
	 */
	public function view() : FormType
	{

        $form = $this->form();

        $form->setTitle(__('Create New Pick List'));
        $form->setButtons((new SubmitButtonType(__('Create'), $form->getAction())));

        $picklist = (new SectionType(__('Details')))->addChildren(
            RowType::withChildren($form->get('label')),
            RowType::withChildren($form->get('description')),
	        RowType::withChildren($form->get('is_tag'))
        );

        $form->addChildren(
            $picklist
        );

        return $form;
	}
}
