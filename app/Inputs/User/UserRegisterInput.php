<?php

namespace Foundry\System\Inputs\User;

use Foundry\Core\Inputs\Inputs;
use Foundry\Core\Inputs\Types\FormType;
use Foundry\Core\Inputs\Types\RowType;
use Foundry\Core\Inputs\Types\SectionType;
use Foundry\Core\Inputs\Types\SubmitButtonType;
use Foundry\Core\Inputs\Traits\ViewableInput;
use Foundry\Core\Requests\Contracts\ViewableInputInterface;
use Foundry\Core\Support\InputTypeCollection;
use Foundry\System\Inputs\User\Types\DisplayName;
use Foundry\System\Inputs\User\Types\Email;
use Foundry\System\Inputs\User\Types\Password;
use Foundry\System\Inputs\User\Types\PasswordConfirmation;
use Foundry\System\Inputs\User\Types\Username;
use Illuminate\Http\Request;

/**
 * Class UserRegisterInput
 *
 * @package Foundry\System\Inputs
 *
 * @property $username
 * @property $display_name
 * @property $email
 * @property $password
 * @property $super_admin
 */
class UserRegisterInput extends Inputs implements ViewableInputInterface
{
    use ViewableInput;

	protected $fillable = [
		'username',
		'display_name',
		'email',
		'password',
		'password_confirmation'
	];

	public function types() : InputTypeCollection
	{
		return InputTypeCollection::fromTypes([
			Username::input()->addRule('unique:system_users,username')
			                 ->setHelp(__('A unique username that is URL friendly. Must only contain letters, numbers or _.')),
			DisplayName::input()->addRule('unique:system_users,display_name'),
			Email::input()->addRule('unique:system_users,email'),
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

        $form->setTitle(__('Create And Account'));
        $form->setButtons((new SubmitButtonType(__('Register'), $form->getAction())));
        $form->addChildren(
            (new SectionType(__('Details')))->addChildren(
                RowType::withChildren($form->get('username'), $form->get('display_name')),
                RowType::withChildren($form->get('email'))
            )
        );
        $form->addChildren(
            (new SectionType(__('Password')))->addChildren(
                RowType::withChildren($form->get('password'), $form->get('password_confirmation'))
            )
        );
        return $form;
    }
}
