<?php

namespace Foundry\System\Inputs\User;

use Foundry\Core\Inputs\Inputs;
use Foundry\Core\Inputs\Types\FormType;
use Foundry\Core\Inputs\Types\SubmitButtonType;
use Foundry\Core\Inputs\Traits\ViewableInput;
use Foundry\Core\Requests\Contracts\ViewableInputInterface;
use Foundry\Core\Support\InputTypeCollection;
use Foundry\System\Inputs\Types\Token;
use Foundry\System\Inputs\User\Types\Email;
use Foundry\System\Inputs\User\Types\Password;
use Foundry\System\Inputs\User\Types\PasswordConfirmation;
use Illuminate\Http\Request;

/**
 * Class ResetPasswordInput
 *
 * @package Foundry\System\Inputs
 *
 * @property $token
 * @property $email
 * @property $password
 * @property $super_admin
 */
class ResetPasswordInput extends Inputs implements ViewableInputInterface
{
    use ViewableInput;

	protected $fillable = [
		'token',
		'email',
		'password',
		'password_confirmation'
	];

	public function types() : InputTypeCollection
	{
		return InputTypeCollection::fromTypes([
			Token::input()->setHelp(__('This was the code you received in the reset email')),
			Email::input(),
			Password::input()->addRule('min:8')->addRule('max:20')->addRule('confirmed:password_confirmation'),
			PasswordConfirmation::input()
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

        $form->setTitle(__('Reset Password'));
        $form->setButtons((new SubmitButtonType(__('Reset Password'), $form->getAction())));
        $form->addChildren(
            Token::input(),
            Email::input(),
            Password::input()->addRule('min:8')->addRule('max:20')->addRule('confirmed:password_confirmation'),
            PasswordConfirmation::input()
        );
        return $form;
    }
}
