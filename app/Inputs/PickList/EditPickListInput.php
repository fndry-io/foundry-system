<?php

namespace Foundry\System\Inputs\PickList;

use Foundry\Core\Inputs\Types\FormType;
use Foundry\Core\Inputs\Types\SubmitButtonType;

/**
 * Class ChecklistInput
 *
 * @package Modules\Foundry\PickLists\Inputs
 *
 * @property $name
 */
class EditPickListInput extends AddPickListInput
{
    /**
     * Make a viewable DocType for the request
     *
     * @return FormType
     */
    public function view($request) : FormType
    {
        $form = parent::view($request);

        $form->setTitle(__('Update Pick List'));
        $form->setButtons((new SubmitButtonType(__('Save'), $form->getAction())));

        return $form;
    }
}
