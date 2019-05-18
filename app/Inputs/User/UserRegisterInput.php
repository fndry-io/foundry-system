<?php

namespace Foundry\System\Inputs\User;

use Foundry\Core\Inputs\Inputs;
use Foundry\Core\Support\InputTypeCollection;
use Foundry\System\Inputs\User\Types\Email;
use Foundry\System\Inputs\User\Types\FirstName;
use Foundry\System\Inputs\User\Types\LastName;
use Foundry\System\Inputs\User\Types\Password;
use Foundry\System\Inputs\User\Types\PasswordConfirmation;

/**
 * Class UserRegisterInput
 *
 * @package Foundry\System\Inputs
 *
 * @property $first_name
 * @property $last_name
 * @property $email
 * @property $password
 * @property $super_admin
 */
class UserRegisterInput extends Inputs {

	protected $fillable = [
		'first_name',
		'last_name',
		'email',
		'password',
		'password_confirmation'
	];

	public function types() : InputTypeCollection
	{
		return InputTypeCollection::fromTypes([
			FirstName::input(),
			LastName::input(),
			Email::input(),
			Password::input()->addRule('min:8')->addRule('max:20')->addRule('confirm'),
			PasswordConfirmation::input()
		]);
	}

}