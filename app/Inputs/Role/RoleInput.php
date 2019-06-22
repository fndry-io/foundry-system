<?php

namespace Foundry\System\Inputs\Role;

use Foundry\Core\Inputs\Inputs;
use Foundry\Core\Support\InputTypeCollection;
use Foundry\System\Inputs\Role\Types\Name;

/**
 * Class RoleAddInput
 *
 * @package Foundry\System\Inputs
 *
 * @property $name
 */
class RoleInput extends Inputs {

	protected $fillable = [
		'name'
	];

	public function types() : InputTypeCollection
	{
		return InputTypeCollection::fromTypes([
			Name::input()
		]);
	}

}