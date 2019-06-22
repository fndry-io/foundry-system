<?php

namespace Foundry\System\Inputs\User;

use Foundry\Core\Inputs\Inputs;
use Foundry\Core\Inputs\Types\HiddenInputType;
use Foundry\Core\Support\InputTypeCollection;
use Foundry\System\Inputs\User\Types\Email;
use Foundry\System\Inputs\User\Types\Password;
use Foundry\System\Inputs\User\Types\RememberMe;

/**
 * Class UserLogoutInput
 *
 * @package Foundry\System\Inputs
 *
 * @property $guard
 */
class UserLogoutInput extends Inputs {

	protected $fillable = [
		'guard'
	];

	public function types() : InputTypeCollection
	{
		return InputTypeCollection::fromTypes([
			(new HiddenInputType('guard'))->setRequired(false)
		]);
	}

}