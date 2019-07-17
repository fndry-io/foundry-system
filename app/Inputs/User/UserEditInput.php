<?php

namespace Foundry\System\Inputs\User;

use Foundry\Core\Inputs\Inputs;
use Foundry\Core\Support\InputTypeCollection;
use Foundry\System\Inputs\User\Types\Email;
use Foundry\System\Inputs\User\Types\Username;
use Foundry\System\Inputs\User\Types\DisplayName;
use Foundry\System\Inputs\User\Types\Password;
use Foundry\System\Inputs\User\Types\PasswordConfirmation;

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

	protected $fillable = [
		'username',
		'display_name',
		'email',
		'password',
		'password_confirmation'
	];

	public function types() : InputTypeCollection
	{
		$types = [
			Username::input()->addRule('unique:Foundry\System\Entities\User,username')
			                 ->setHelp(__('A unique username that is URL friendly. Must only contain letters, numbers or _.')),
			DisplayName::input()->addRule('unique:Foundry\System\Entities\User,display_name'),
			Email::input()->addRule('unique:Foundry\System\Entities\User,email'),
			Password::input()->addRule('min:8')->addRule('max:20')->addRule('confirmed:password_confirmation')->setRequired(false),
			PasswordConfirmation::input()->setRequired(false)
		];
		return InputTypeCollection::fromTypes($types);
	}

}