<?php

namespace Foundry\System\Inputs\User;

use Foundry\Core\Inputs\Inputs;
use Foundry\Core\Inputs\Types\FormType;
use Foundry\Core\Inputs\Types\HiddenInputType;
use Foundry\Core\Inputs\Types\SubmitButtonType;
use Foundry\Core\Inputs\Types\TagType;
use Foundry\Core\Inputs\Types\Traits\ViewableInput;
use Foundry\Core\Requests\Contracts\ViewableInputInterface;
use Foundry\Core\Support\InputTypeCollection;
use Illuminate\Http\Request;

/**
 * Class UserLogoutInput
 *
 * @package Foundry\System\Inputs
 *
 * @property $guard
 */
class UserLogoutInput extends Inputs implements ViewableInputInterface
{
    use ViewableInput;

	protected $fillable = [
		'guard'
	];

	public function types() : InputTypeCollection
	{
		return InputTypeCollection::fromTypes([
			(new HiddenInputType('guard'))->setRequired(false)
		]);
	}

    /**
     * Make a viewable DocType for the request
     *
     * @return FormType
     */
    public function view(Request $request) : FormType
    {
        $form = $this->form($request);
        $form->setTitle(__('Log Out'));
        $form->addChildren((new TagType('div', __('You will now be logged out of the session'))));
        $form->setButtons((new SubmitButtonType(__('Log Out'), $form->getAction())));
        return $form;
    }
}
