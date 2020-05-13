<?php

namespace Foundry\System\Inputs\PickList;

use Foundry\Core\Inputs\Types\FormType;
use Foundry\Core\Inputs\Types\RowType;
use Foundry\Core\Inputs\Types\SectionType;
use Foundry\Core\Inputs\Types\SubmitButtonType;
use Foundry\Core\Inputs\Traits\ViewableInput;
use Foundry\Core\Requests\Contracts\ViewableInputInterface;

/**
 * Class ChecklistInput
 *
 * @package Modules\Foundry\PickLists\Inputs
 *
 * @property $name
 */
class AddPickListInput extends PickListInput
{
    public function view($request) : FormType
    {
        $form = parent::view($request);

        $form->setTitle(__('Create New Pick List'));
        $form->setButtons((new SubmitButtonType(__('Create'), $form->getAction())));

        return $form;
    }
}
