<?php

namespace Foundry\System\Inputs\Address\Types;

use Foundry\Core\Inputs\Contracts\Field;
use Foundry\Core\Entities\Entity;
use Foundry\Core\Inputs\Contracts\FieldOptions;
use Foundry\Core\Inputs\Types\ChoiceInputType;
use Foundry\Core\Inputs\Types\Contracts\Inputable;
use Foundry\System\Inputs\Types\Traits\PickListable;

class Type extends ChoiceInputType implements Field {

    use PickListable;
	/**
	 * @param Entity|null $entity
	 *
	 * @return Inputable|Type
	 */
	static function input( Entity &$entity = null ): Inputable {
		return ( new static(
			'type',
			__( 'Type' ),
			false
		) )
			->setDefault('main')
			->setSortable( true )
            ->setPickList('address_type', 'identifier')
            ;
	}


}