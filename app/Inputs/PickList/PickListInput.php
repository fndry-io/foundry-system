<?php

namespace Foundry\System\Inputs\PickList;

use Foundry\Core\Inputs\Inputs;
use Foundry\Core\Inputs\Types\FormType;
use Foundry\Core\Inputs\Types\RowType;
use Foundry\Core\Inputs\Types\SectionType;
use Foundry\Core\Inputs\Types\SubmitButtonType;
use Foundry\Core\Inputs\Types\Traits\ViewableInput;
use Foundry\Core\Requests\Contracts\ViewableInputInterface;
use Foundry\Core\Support\InputTypeCollection;
use Foundry\System\Inputs\PickListItem\Types\Label;
use Foundry\System\Inputs\PickList\Types\Description;
use Foundry\System\Inputs\PickList\Types\IsTag;

/**
 * Class ChecklistInput
 *
 * @package Modules\Foundry\PickLists\Inputs
 *
 * @property $name
 */
class PickListInput extends Inputs implements ViewableInputInterface
{
    use ViewableInput;

	public function types() : InputTypeCollection
	{
		return InputTypeCollection::fromTypes([
			Label::input(),
			Description::input(),
			IsTag::input()
		]);
	}

    /**
     * Make a viewable DocType for the request
     *
     * @return FormType
     */
    public function view($request) : FormType
    {
        $form = $this->form($request);

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
