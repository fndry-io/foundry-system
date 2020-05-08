<?php

namespace Foundry\System\Inputs\User;

use Foundry\Core\Inputs\Inputs;
use Foundry\Core\Inputs\Types\FormType;
use Foundry\Core\Inputs\Types\RowType;
use Foundry\Core\Inputs\Types\SectionType;
use Foundry\Core\Inputs\Types\SubmitButtonType;
use Foundry\Core\Inputs\Types\Traits\ViewableInput;
use Foundry\Core\Requests\Contracts\ViewableInputInterface;
use Foundry\Core\Support\InputTypeCollection;
use Foundry\System\Http\Requests\Users\EditUserRequest;
use Foundry\System\Inputs\Types\User;
use Foundry\System\Inputs\User\Types\Active;
use Foundry\System\Inputs\User\Types\DisplayName;
use Foundry\System\Inputs\User\Types\Email;
use Foundry\System\Inputs\User\Types\Password;
use Foundry\System\Inputs\User\Types\PasswordConfirmation;
use Foundry\System\Inputs\User\Types\ProfileImage;
use Foundry\System\Inputs\User\Types\Roles;
use Foundry\System\Inputs\User\Types\SuperAdmin;
use Foundry\System\Inputs\User\Types\Username;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

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
class UserEditInput extends Inputs implements ViewableInputInterface
{
    use ViewableInput;

    public function rules()
    {
        $rules = parent::rules();
        $entity = $this->getEntity();

        if ($entity) {
            $rules['email'] = [
                'required',
                'email',
                Rule::unique('system_users', 'email')->ignore($entity->getKey())
            ];
            $rules['username'] = [
                'required',
                'username',
                Rule::unique('system_users', 'username')->ignore($entity->getKey())
            ];
            $rules['display_name'] = [
                'required',
                Rule::unique('system_users', 'display_name')->ignore($entity->getKey())
            ];
        }
        return $rules;
    }

	public function types() : InputTypeCollection
	{
		$types = [
			Username::input()->addRule('unique:system_users,username')
			                 ->setHelp(__('A unique username that is URL friendly. Must only contain letters, numbers or _.')),
			DisplayName::input()->addRule('unique:system_users,display_name'),
			Email::input()->addRule('unique:system_users,email'),
			Password::input()->addRule('min:8')->addRule('max:20')->addRule('confirmed:password_confirmation')->setRequired(false),
			PasswordConfirmation::input()->setRequired(false),
            ProfileImage::input()
		];

		if (Auth::user()->isAdmin() || Auth::user()->isSuperAdmin()) {
            $types[] = Roles::input();
			$types[] = Active::input();
		}

		if (Auth::user()->isSuperAdmin()) {
			$types[] = SuperAdmin::input();
		}

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
        $entity = $request->getEntity();

        $form->setTitle(__('Edit User'));
        $form->setButtons((new SubmitButtonType(__('Save'), $form->getAction())));
        $form->addChildren(
            (new SectionType(__('Details')))->addChildren(
                RowType::withChildren($form->get('username')->setAutocomplete(false), $form->get('display_name')->setAutocomplete(false)),
                RowType::withChildren($form->get('email')->setAutocomplete(false))
            )
        );

        if (Auth::user()->id === $entity->getKey() || Auth::user()->isAdmin() || Auth::user()->isSuperAdmin()) {
            $form->addChildren(
                (new SectionType(__('Password'), __('If you need to change the password set the values below or leave them blank to leave the password as is.')))->addChildren(
                    RowType::withChildren($form->get('password')->setValue(null)->setAutocomplete(false), $form->get('password_confirmation')->setValue(null)->setAutocomplete(false))
                )
            );
        }

        if ($entity->getKey() !== Auth::user()->getKey() && $entity->getKey() !== 1 && (Auth::user()->isAdmin() || Auth::user()->isSuperAdmin())) {
            $children = [];
            $children[] = $form->get('active');
            if (Auth::user()->isSuperAdmin()) {
                $children[] = $form->get('super_admin');
            }
            $form->addChildren(
                (new SectionType(__('Access'), __('Controls the access this user has to the system.')))->addChildren(
                    RowType::withChildren(...$children),
                    RowType::withChildren($form->get('roles')->setValue($entity->roles->pluck('id')->toArray()))
                )
            );
        }

        return $form;
    }
}
