<?php

namespace Foundry\System\Inputs\PickListItem;

use Foundry\Core\Inputs\Inputs;
use Foundry\Core\Inputs\Traits\ViewableInput;
use Foundry\Core\Inputs\Types\FormType;
use Foundry\Core\Inputs\Types\HiddenInputType;
use Foundry\Core\Inputs\Types\RowType;
use Foundry\Core\Inputs\Types\SectionType;
use Foundry\Core\Inputs\Types\SubmitButtonType;
use Foundry\Core\Requests\Contracts\ViewableInputInterface;
use Foundry\Core\Support\InputTypeCollection;
use Foundry\System\Inputs\PickListItem\Types\DefaultItem;
use Foundry\System\Inputs\PickListItem\Types\Description;
use Foundry\System\Inputs\PickListItem\Types\Label;
use Foundry\System\Inputs\PickListItem\Types\Sequence;
use Foundry\System\Inputs\PickListItem\Types\Status;
use Illuminate\Http\Request;


/**
 * Class ChecklistInput
 *
 * @package Modules\Foundry\Checklists\Inputs
 *
 * @property $name
 */
class PickListEditItemInput extends PickListItemInput implements ViewableInputInterface
{
    use ViewableInput;

    /**
     * Make a viewable DocType for the request
     *
     * @param Request $request
     * @return FormType
     * @throws \Exception
     */
    public function view(Request $request) : FormType
    {
        $form = $this->form();

        $form->setTitle(__('Update Pick List Item'));
        $form->setButtons((new SubmitButtonType(__('Update'), $form->getAction())));

        $entity = $this->getEntity();

        $item = (new SectionType(__('Details')))->addChildren(
            RowType::withChildren($form->get('label')),
            RowType::withChildren($form->get('description')),
            RowType::withChildren($form->get('status'), $form->get('default_item')->setValue(($entity->picklist->default_item === $entity->getKey()))),
            RowType::withChildren($form->get('sequence'))
        );

        $form->addChildren(
            $item
        );

        return $form;
    }

}
