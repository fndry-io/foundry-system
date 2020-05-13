<?php

namespace Foundry\System\Inputs\Role;

use Foundry\Core\Inputs\Inputs;
use Foundry\Core\Inputs\Types\FormType;
use Foundry\Core\Inputs\Types\RowType;
use Foundry\Core\Inputs\Types\SectionType;
use Foundry\Core\Inputs\Types\SubmitButtonType;
use Foundry\Core\Inputs\Traits\ViewableInput;
use Foundry\Core\Requests\Contracts\ViewableInputInterface;
use Foundry\Core\Support\InputTypeCollection;
use Foundry\System\Inputs\Role\Types\Guard;
use Foundry\System\Inputs\Role\Types\Name;

/**
 * Class RoleInput
 *
 * @package Foundry\System\Inputs
 *
 * @property $name
 */
class EditRoleInput extends RoleInput
{
    public function view($request) : FormType
    {
        $form = parent::view($request);

        $form->setTitle(__('Create Role'));
        $form->setButtons((new SubmitButtonType(__('Create'), $form->getAction())));

        return $form;
    }

}
