<?php

namespace Foundry\System\Inputs\Address\Types;

use Foundry\Core\Inputs\Contracts\Field;
use Foundry\Core\Entities\Entity;
use Foundry\Core\Inputs\Contracts\FieldOptions;
use Foundry\Core\Inputs\Types\ChoiceInputType;
use Foundry\Core\Inputs\Types\Contracts\Inputable;

class Country extends ChoiceInputType implements Field {

    use PickListable;
	/**
	 * @param Entity|null $entity
	 *
	 * @return Inputable|Country
	 */
	static function input( Entity &$entity = null ): Inputable {
		return ( new static(
			'country',
			__( 'Country' ),
			false
		) )
			->setDefault('US')
			->setSortable( true)
            ->setPickList('country', 'identifier')
            ;
	}


}