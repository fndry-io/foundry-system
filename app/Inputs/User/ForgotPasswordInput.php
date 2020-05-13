<?php

namespace Foundry\System\Inputs\User;

use Foundry\Core\Inputs\Inputs;
use Foundry\Core\Inputs\Types\FormType;
use Foundry\Core\Inputs\Types\RowType;
use Foundry\Core\Inputs\Types\SubmitButtonType;
use Foundry\Core\Inputs\Types\TagType;
use Foundry\Core\Inputs\Traits\ViewableInput;
use Foundry\Core\Requests\Contracts\ViewableInputInterface;
use Foundry\Core\Support\InputTypeCollection;
use Foundry\System\Inputs\User\Types\Email;
use Illuminate\Http\Request;

/**
 * Class UserForgotPasswordInput
 *
 * @package Foundry\System\Inputs
 *
 * @property $email
 */
class ForgotPasswordInput extends Inputs implements ViewableInputInterface
{
    use ViewableInput;

	protected $fillable = [
		'email'
	];

	public function types() : InputTypeCollection
	{
		return InputTypeCollection::fromTypes([
			Email::input()
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

        $form->setTitle(__('Forgot your Password?'));
        $form->addChildren((new TagType('p', __("Don't worry. Resetting your password is easy. Just tell us the email address you used to register with."))));
        $form->setButtons((new SubmitButtonType(__('Send'), $form->getAction())));
        $form->addChildren(
            RowType::withChildren($form->get('email'))
        );
        return $form;
    }
}
