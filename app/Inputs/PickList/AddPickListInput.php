<?php

namespace Foundry\System\Inputs\PickList;

use Foundry\Core\Inputs\Types\FormType;
use Foundry\Core\Inputs\Types\RowType;
use Foundry\Core\Inputs\Types\SectionType;
use Foundry\Core\Inputs\Types\SubmitButtonType;
use Foundry\Core\Inputs\Types\Traits\ViewableInput;
use Foundry\Core\Requests\Contracts\ViewableInputInterface;

/**
 * Class ChecklistInput
 *
 * @package Modules\Foundry\PickLists\Inputs
 *
 * @property $name
 */
class AddPickListInput extends PickListInput implements ViewableInputInterface
{
    use ViewableInput;

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
