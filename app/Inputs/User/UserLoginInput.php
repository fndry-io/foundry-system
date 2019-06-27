<?php

namespace Foundry\System\Inputs\User;

use Foundry\Core\Inputs\Inputs;
use Foundry\Core\Inputs\Types\HiddenInputType;
use Foundry\Core\Support\InputTypeCollection;
use Foundry\System\Inputs\User\Types\Email;
use Foundry\System\Inputs\User\Types\Password;
use Foundry\System\Inputs\User\Types\RememberMe;

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
class UserLoginInput extends Inputs {

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

}