<?php

namespace Foundry\System\Inputs\PickList;

use Foundry\Core\Inputs\Types\FormType;
use Foundry\Core\Inputs\Types\RowType;
use Foundry\Core\Inputs\Types\SectionType;
use Foundry\Core\Inputs\Types\SubmitButtonType;
use Foundry\Core\Inputs\Types\Traits\ViewableInput;
use Foundry\Core\Requests\Contracts\ViewableInputInterface;
use Foundry\System\Inputs\PickListItem\PickListItemInput;
use Illuminate\Http\Request;

/**
 * Class ChecklistInput
 *
 * @package Modules\Foundry\PickLists\Inputs
 *
 * @property $name
 */
class AddPickListItemInput extends PickListItemInput implements ViewableInputInterface
{
    use ViewableInput;

    /**
     * Make a viewable DocType for the request
     *
     * @return FormType
     */
    public function view(Request $request) : FormType
    {
        $form = $this->form($request);
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
