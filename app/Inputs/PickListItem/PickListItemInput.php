<?php

namespace Foundry\System\Inputs\PickListItem;

use Foundry\Core\Inputs\Inputs;
use Foundry\Core\Inputs\Types\HiddenInputType;
use Foundry\Core\Support\InputTypeCollection;
use Foundry\System\Inputs\PickListItem\Types\DefaultItem;
use Foundry\System\Inputs\PickListItem\Types\Description;
use Foundry\System\Inputs\PickListItem\Types\Label;
use Foundry\System\Inputs\PickListItem\Types\Sequence;
use Foundry\System\Inputs\PickListItem\Types\Status;

/**
 * Class ChecklistInput
 *
 * @package Modules\Foundry\Checklists\Inputs
 *
 * @property $name
 */
class PickListItemInput extends Inputs {


	public function types() : InputTypeCollection
	{
		return InputTypeCollection::fromTypes([
			Label::input(),
			Description::input(),
			Sequence::input(),
			Status::input(),
            DefaultItem::input()
		]);
	}

}
