<?php

namespace Foundry\System\Inputs\User;

use Foundry\Core\Inputs\Inputs;
use Foundry\Core\Support\InputTypeCollection;
use Foundry\System\Inputs\User\Types\Email;
use Foundry\System\Inputs\User\Types\Password;

/**
 * Class UserLoginInput
 *
 * @package Foundry\System\Inputs
 *
 * @property $email
 * @property $password
 */
class UserLoginInput extends Inputs {

	protected $fillable = [
		'email',
		'password'
	];

	public function types() : InputTypeCollection
	{
		return InputTypeCollection::fromTypes([
			Email::input(),
			Password::input()
		]);
	}

}