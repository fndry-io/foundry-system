<?php

namespace Foundry\System\Http\Requests\PickListItems;

use Foundry\Core\Inputs\Types\FormType;
use Foundry\Core\Inputs\Types\RowType;
use Foundry\Core\Inputs\Types\SectionType;
use Foundry\Core\Inputs\Types\SubmitButtonType;
use Foundry\Core\Requests\Contracts\InputInterface;
use Foundry\Core\Requests\Contracts\ViewableFormRequestInterface;
use Foundry\Core\Requests\FormRequest;
use Foundry\Core\Requests\Response;
use Foundry\Core\Requests\Traits\HasInput;
use Foundry\System\Inputs\PickListItem\PickListItemInput;
use Foundry\System\Services\PickListItemService;


class AddPickListItemRequest extends FormRequest implements InputInterface, ViewableFormRequestInterface
{
	use HasInput;

	/**
	 * @return String
	 */
	public static function name(): String {
		return 'foundry.system.pick-list-items.add';
	}

	/**
	 * @param $inputs
	 *
	 * @return string
	 */
	public function makeInput($inputs) {
		return new PickListItemInput($inputs);
	}

	/**
	 * @return bool
	 */
	public function authorize(): bool
	{
		//todo add in the authorisation
		return !!($this->user());
	}

	/**
	 * Handle the request
	 *
	 * @return Response
	 * @throws \Doctrine\ORM\ORMException
	 */
	public function handle() : Response
	{
		return PickListItemService::service()->add($this->input);
	}

	/**
	 * Make a viewable DocType for the request
	 *
	 * @return FormType
	 */
	public function view() : FormType
	{
        $form = $this->form();

        $form->setTitle(__('Create New Pick List Item'));
        $form->setButtons((new SubmitButtonType(__('Create'), $form->getAction())));

		$item = (new SectionType(__('Details')))->addChildren(
            RowType::withChildren($form->get('name')),
            RowType::withChildren($form->get('description')),
            RowType::withChildren($form->get('status'), $form->get('default_item')),
            RowType::withChildren($form->get('sequence'))
		);

        $form->addChildren(
            $item,
            $form->get('picklist')
        );

        return $form;
	}
}
