<?php

namespace Foundry\System\Inputs\User;

use Foundry\Core\Inputs\Inputs;
use Foundry\Core\Support\InputTypeCollection;
use Foundry\System\Inputs\User\Types\Email;

/**
 * Class UserForgotPasswordInput
 *
 * @package Foundry\System\Inputs
 *
 * @property $email
 */
class ForgotPasswordInput extends Inputs {

	protected $fillable = [
		'email'
	];

	public function types() : InputTypeCollection
	{
		return InputTypeCollection::fromTypes([
			Email::input()
		]);
	}

}