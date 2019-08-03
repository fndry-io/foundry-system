<?php

namespace Foundry\System\Inputs\PickList;

use Foundry\Core\Inputs\Inputs;
use Foundry\Core\Support\InputTypeCollection;
use Foundry\System\Inputs\PickList\Types\Description;
use Foundry\System\Inputs\PickList\Types\Name;

/**
 * Class ChecklistInput
 *
 * @package Modules\Foundry\PickLists\Inputs
 *
 * @property $name
 */
class PickListInput extends Inputs {

	public function types() : InputTypeCollection
	{
		return InputTypeCollection::fromTypes([
			Name::input(),
			Description::input()
		]);
	}

}