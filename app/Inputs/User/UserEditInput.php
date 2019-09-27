<?php

namespace Foundry\System\Inputs\User;

use Foundry\Core\Inputs\Inputs;
use Foundry\Core\Support\InputTypeCollection;
use Foundry\System\Inputs\Types\User;
use Foundry\System\Inputs\User\Types\Active;
use Foundry\System\Inputs\User\Types\Email;
use Foundry\System\Inputs\User\Types\JobDepartment;
use Foundry\System\Inputs\User\Types\JobTitle;
use Foundry\System\Inputs\User\Types\SuperAdmin;
use Foundry\System\Inputs\User\Types\Username;
use Foundry\System\Inputs\User\Types\DisplayName;
use Foundry\System\Inputs\User\Types\Password;
use Foundry\System\Inputs\User\Types\PasswordConfirmation;
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
class UserEditInput extends Inputs {

	public function types() : InputTypeCollection
	{
		$types = [
			Username::input()->addRule('unique:users,username')
			                 ->setHelp(__('A unique username that is URL friendly. Must only contain letters, numbers or _.')),
			DisplayName::input()->addRule('unique:users,display_name'),
			Email::input()->addRule('unique:users,email'),
			Password::input()->addRule('min:8')->addRule('max:20')->addRule('confirmed:password_confirmation')->setRequired(false),
			PasswordConfirmation::input()->setRequired(false),
			JobTitle::input(),
			JobDepartment::input(),
			User::input()->setLabel(__('Supervisor'))->setName('supervisor')
		];

		if (Auth::user()->isAdmin() || Auth::user()->isSuperAdmin()) {
			$types[] = Active::input();
			//todo add role
		}

		if (Auth::user()->isSuperAdmin()) {
			$types[] = SuperAdmin::input();
		}

		return InputTypeCollection::fromTypes($types);
	}

}