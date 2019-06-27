<?php

namespace Foundry\System\Inputs\Types;

use Foundry\Core\Inputs\Contracts\Field;
use Foundry\Core\Entities\Entity;
use Foundry\Core\Inputs\Types\TelInputType;
use Foundry\Core\Inputs\Types\Contracts\Inputable;

class Telephone extends TelInputType implements Field {

	/**
	 * @param Entity|null $entity
	 *
	 * @return Inputable|Telephone
	 */
	static function input( Entity &$entity = null ): Inputable {
		return ( new static(
			'telephone',
			__( 'Telephone' ),
			true
		) )
			->setMax( 10 );
	}

}