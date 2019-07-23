<?php

namespace Foundry\System\Inputs\Address\Types;

use Foundry\Core\Inputs\Contracts\Field;
use Foundry\Core\Entities\Entity;
use Foundry\Core\Inputs\Types\TextInputType;
use Foundry\Core\Inputs\Types\Contracts\Inputable;

class Code extends TextInputType implements Field {

	/**
	 * @param Entity|null $entity
	 *
	 * @return Inputable|Code
	 */
	static function input( Entity &$entity = null ): Inputable {
		return ( new static(
			'code',
			__( 'Zip Code' ),
			false
		) )
			->setMax( 10 )
			->setSortable( true );
	}

}