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
class RoleInput extends Inputs implements ViewableInputInterface
{
    use ViewableInput;

	public function types() : InputTypeCollection
	{
		return InputTypeCollection::fromTypes([
			Name::input(),
            Guard::input()
		]);
	}

    public function view($request) : FormType
    {
        $form = $this->form($request);

        $form->setTitle(__('Create Role'));
        $form->setButtons((new SubmitButtonType(__('Create'), $form->getAction())));

        $form->addChildren(
            (new SectionType(__('Role')))->addChildren(
                RowType::withChildren($form->get('name')),
                RowType::withChildren($form->get('guard_name'))
            )
        );

        return $form;
    }

}
