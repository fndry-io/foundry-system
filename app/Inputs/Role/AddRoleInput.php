<?php

namespace Foundry\System\Inputs\Role;

use Foundry\Core\Inputs\Types\FormType;
use Foundry\Core\Inputs\Types\SubmitButtonType;

/**
 * Class RoleInput
 *
 * @package Foundry\System\Inputs
 *
 * @property $name
 */
class AddRoleInput extends RoleInput
{
    public function view($request) : FormType
    {
        $form = parent::view($request);

        $form->setTitle(__('Add Role'));
        $form->setButtons((new SubmitButtonType(__('Create'), $form->getAction())));

        return $form;
    }

}
