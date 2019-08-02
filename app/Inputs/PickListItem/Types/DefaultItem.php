<?php

namespace Foundry\System\Inputs\PickListItem\Types;

use Foundry\Core\Inputs\Contracts\Field;
use Foundry\Core\Inputs\Types\CheckboxInputType;
use Foundry\Core\Inputs\Types\ChoiceInputType;
use Foundry\Core\Inputs\Types\Contracts\Inputable;
use Foundry\Core\Inputs\Types\NumberInputType;
use Foundry\Core\Entities\Entity;

class DefaultItem extends CheckboxInputType implements Field {

	static function input( Entity &$entity = null ): Inputable {
		return ( new static(
			'default_item',
			__( 'Default Item' ),
			false )
		)
			->setSortable( true )
            ->setDefault(false)
            ;
	}

}