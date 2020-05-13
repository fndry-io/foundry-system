<?php

namespace Foundry\System\Inputs\User;

use Foundry\Core\Inputs\Inputs;
use Foundry\Core\Inputs\Types\FormType;
use Foundry\Core\Inputs\Types\HiddenInputType;
use Foundry\Core\Inputs\Types\RowType;
use Foundry\Core\Inputs\Types\SubmitButtonType;
use Foundry\Core\Inputs\Traits\ViewableInput;
use Foundry\Core\Requests\Contracts\ViewableInputInterface;
use Foundry\Core\Support\InputTypeCollection;
use Foundry\System\Inputs\User\Types\Email;
use Foundry\System\Inputs\User\Types\Password;
use Illuminate\Http\Request;

/**
 * Class UserLoginInput
 *
 * @package Foundry\System\Inputs
 *
 * @property $email
 * @property $password
 * @property $guard
 * @property $remember_me
 */
class UserLoginInput extends Inputs implements ViewableInputInterface
{
    use ViewableInput;

	protected $fillable = [
		'email',
		'password',
		'guard'
	];

	public function types() : InputTypeCollection
	{
		return InputTypeCollection::fromTypes([
			Email::input(),
			Password::input(),
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

        $form->setTitle(__('Login'));
        $form->setButtons((new SubmitButtonType(__('Log In'), $form->getAction())));
        $form->addChildren(
            RowType::withChildren($form->get('email')),
            RowType::withChildren($form->get('password'))
        );
        return $form;
    }
}
