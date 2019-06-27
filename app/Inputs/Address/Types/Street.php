<?php

namespace Foundry\System\Inputs\Address\Types;

use Foundry\Core\Inputs\Contracts\Field;
use Foundry\Core\Entities\Entity;
use Foundry\Core\Inputs\Types\TextInputType;
use Foundry\Core\Inputs\Types\Contracts\Inputable;

class Street extends TextInputType implements Field {

	/**
	 * @param Entity|null $entity
	 *
	 * @return Inputable|Street
	 */
	static function input( Entity &$entity = null ): Inputable {
		return ( new static(
			'street',
			__( 'Street' ),
			false
		) )
			->setMax( 100 )
			->setSortable( true );
	}

}