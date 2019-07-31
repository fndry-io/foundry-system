<?php

namespace Foundry\System\Inputs\File\Types;

use Foundry\Core\Inputs\Contracts\Field;
use Foundry\Core\Entities\Entity;
use Foundry\Core\Inputs\Types\TextInputType;
use Foundry\Core\Inputs\Types\Contracts\Inputable;

class Type extends TextInputType implements Field {

	/**
	 * @param Entity|null $entity
	 *
	 * @return Inputable|Type
	 */
	static function input( Entity &$entity = null ): Inputable {
		return ( new static(
			'type',
			__( 'Type' ),
			true
		) )
			->setMax( 255 )
			->setSortable( true );
	}

}