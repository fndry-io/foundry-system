<?php

namespace Foundry\System\Inputs\Address\Types;

use Foundry\Core\Inputs\Contracts\Field;
use Foundry\Core\Entities\Entity;
use Foundry\Core\Inputs\Types\TextInputType;
use Foundry\Core\Inputs\Types\Contracts\Inputable;

class City extends TextInputType implements Field {

	/**
	 * @param Entity|null $entity
	 *
	 * @return Inputable|City
	 */
	static function input( Entity &$entity = null ): Inputable {
		return ( new static(
			'city',
			__( 'City' ),
			false
		) )
			->setMax( 50 )
			->setSortable( true );
	}

}