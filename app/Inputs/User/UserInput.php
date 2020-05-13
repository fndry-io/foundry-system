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
use Foundry\System\Inputs\Types\User;
use Foundry\System\Inputs\User\Types\Active;
use Foundry\System\Inputs\User\Types\DisplayName;
use Foundry\System\Inputs\User\Types\Email;
use Foundry\System\Inputs\User\Types\Password;
use Foundry\System\Inputs\User\Types\PasswordConfirmation;
use Foundry\System\Inputs\User\Types\Roles;
use Foundry\System\Inputs\User\Types\SuperAdmin;
use Foundry\System\Inputs\User\Types\Username;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class UserEditInput
 *
 * @package Foundry\System\Inputs
 *
 * @property $username
 * @property $display_name
 * @property $email
 * @property $password
 * @property $super_admin
 */
class UserInput extends Inputs implements ViewableInputInterface
{
    use ViewableInput;

	public function types() : InputTypeCollection
	{
		$types = [
			Username::input()->setHelp(__('A unique username that is URL friendly. Must only contain letters, numbers or _.')),
			DisplayName::input(),
			Email::input(),
			Password::input()->addRule('min:8')->addRule('max:20')->addRule('confirmed:password_confirmation')->setRequired(false),
			PasswordConfirmation::input()->setRequired(false)
		];
		if (Auth::user()->isAdmin() || Auth::user()->isSuperAdmin()) {
			$types[] = Active::input();
            $types[] = Roles::input();
		}

		if (Auth::user()->isSuperAdmin()) {
			$types[] = SuperAdmin::input();
		}
		//todo add roles
		return InputTypeCollection::fromTypes($types);
	}

    /**
     * Make a viewable DocType for the request
     *
     * @return FormType
     */
    public function view(Request $request) : FormType
    {
        $form = $this->form($request);

        $form->setTitle(__('Create User'));
        $form->setButtons((new SubmitButtonType(__('Save'), $form->getAction())));

        $form->addChildren(
            (new SectionType(__('Details')))->addChildren(
                RowType::withChildren($form->get('username')->setAutocomplete(false), $form->get('display_name')->setAutocomplete(false)),
                RowType::withChildren($form->get('email')->setAutocomplete(false))
            )
        );

        $form->addChildren(
            (new SectionType(__('Password')))->addChildren(
                RowType::withChildren($form->get('password')->setAutocomplete(false)->setRequired(true), $form->get('password_confirmation')->setAutocomplete(false)->setRequired(true))
            )
        );

        if (Auth::user()->isAdmin() || Auth::user()->isSuperAdmin()) {
            $children = [];
            $children[] = $form->get('active');

            if (Auth::user()->isSuperAdmin()) {
                $children[] = $form->get('super_admin');
            }

            $form->addChildren(
                (new SectionType(__('Access'), __('Controls the access this user has to the system.')))->addChildren(
                    RowType::withChildren(...$children),
                    RowType::withChildren($form->get('roles'))
                )
            );
        }

        return $form;
    }
}
