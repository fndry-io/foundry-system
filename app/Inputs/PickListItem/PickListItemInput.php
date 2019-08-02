<?php

namespace Foundry\System\Inputs\PickListItem;

use Foundry\Core\Inputs\Inputs;
use Foundry\Core\Inputs\Types\HiddenInputType;
use Foundry\Core\Support\InputTypeCollection;
use Foundry\System\Inputs\PickListItem\Types\DefaultItem;
use Foundry\System\Inputs\PickListItem\Types\Description;
use Foundry\System\Inputs\PickListItem\Types\Name;
use Foundry\System\Inputs\PickListItem\Types\Sequence;
use Foundry\System\Inputs\PickListItem\Types\Slug;
use Foundry\System\Entities\PickList;
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
			Name::input(),
			Description::input(),
			Slug::input(),
			Sequence::input(),
			Status::input(),
            DefaultItem::input(),
            ( new HiddenInputType('picklist'))->setRequired(true)->setRules([
                'exists:Foundry\System\Entities\PickList,id',
            ])
		]);
	}

}