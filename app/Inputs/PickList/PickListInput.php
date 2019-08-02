<?php

namespace Foundry\System\Inputs\PickList;

use Foundry\Core\Inputs\Inputs;
use Foundry\Core\Support\InputTypeCollection;
use Foundry\System\Inputs\PickList\Types\DefaultItem;
use Foundry\System\Inputs\PickList\Types\Description;
use Foundry\System\Inputs\PickList\Types\Name;
use Foundry\System\Inputs\PickList\Types\Slug;
use Foundry\System\Inputs\PickList\Types\Status;


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
			Description::input(),
			Slug::input(),
			Status::input(),
		]);
	}

}