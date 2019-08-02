<?php

namespace Foundry\System\Inputs\PickListItem\Types;

use Foundry\Core\Inputs\Contracts\Field;
use Foundry\Core\Entities\Entity;
use Foundry\Core\Inputs\Types\Contracts\Inputable;
use Foundry\Core\Inputs\Types\NumberInputType;

class Sequence extends NumberInputType implements Field {

	/**
	 * @param Entity|null $entity
	 *
	 * @return Inputable|Sequence
	 */
	static function input( Entity &$entity = null ): Inputable {
		return ( new static(
			'sequence',
			__( 'Sequence' ),
			true
		) )
			->setMax(99)
			->setSortable( true );
	}

}