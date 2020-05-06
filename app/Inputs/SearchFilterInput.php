<?php

namespace Foundry\System\Inputs;

use Foundry\Core\Inputs\Inputs;
use Foundry\Core\Inputs\Types\FormType;
use Foundry\Core\Inputs\Types\RowType;
use Foundry\Core\Inputs\Types\SubmitButtonType;
use Foundry\Core\Inputs\Types\TextInputType;
use Foundry\Core\Inputs\Types\Traits\ViewableInput;
use Foundry\Core\Requests\Contracts\ViewableInputInterface;
use Foundry\Core\Support\InputTypeCollection;

/**
 * Class SearchFilterInput
 *
 * @package Foundry\System\Inputs
 *
 * @property $search
 */
class SearchFilterInput extends Inputs implements ViewableInputInterface
{
    use ViewableInput;

	public function types() : InputTypeCollection
	{
		return InputTypeCollection::fromTypes([
			(new TextInputType('search', 'Search', false))
		]);
	}

    public function view($request) : FormType
    {
        $form = $this->form();

        $form->setTitle(__('Filter Roles'));
        $form->setButtons((new SubmitButtonType(__('Filter'), $form->getAction())));
        $form->addChildren(
            RowType::withChildren($form->get('search'))
        );
        return $form;
    }

}
