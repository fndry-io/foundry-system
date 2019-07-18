<?php

namespace Foundry\System\Inputs\User;

use Foundry\Core\Inputs\Inputs;
use Foundry\Core\Inputs\Types\CheckboxInputType;
use Foundry\Core\Inputs\Types\TextInputType;
use Foundry\Core\Support\InputTypeCollection;

/**
 * Class UsersFilterInput
 *
 * @package Foundry\System\Inputs
 *
 * @property $search
 */
class UsersFilterInput extends Inputs {

	public function types() : InputTypeCollection
	{
		return InputTypeCollection::fromTypes([
			(new CheckboxInputType('deleted', 'Show Deleted', false))->setCast('boolean'),
			(new TextInputType('search', 'Search', false))
		]);
	}

}