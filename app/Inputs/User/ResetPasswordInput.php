<?php

namespace Foundry\System\Inputs\User;

use Foundry\Core\Inputs\Inputs;
use Foundry\Core\Support\InputTypeCollection;
use Foundry\System\Inputs\Types\Token;
use Foundry\System\Inputs\User\Types\Email;
use Foundry\System\Inputs\User\Types\Password;
use Foundry\System\Inputs\User\Types\PasswordConfirmation;

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
class ResetPasswordInput extends Inputs {

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

}