<?php

namespace Foundry\System\Inputs\File\Types;

use Foundry\Core\Inputs\Contracts\Field;
use Foundry\Core\Entities\Entity;
use Foundry\Core\Inputs\Types\TextInputType;
use Foundry\Core\Inputs\Types\Contracts\Inputable;

class Ext extends TextInputType implements Field {

	/**
	 * @param Entity|null $entity
	 *
	 * @return Inputable|Ext
	 */
	static function input( Entity &$entity = null ): Inputable {
		return ( new static(
			'ext',
			__( 'Ext' ),
			true
		) )
			->setMax( 5 )
			->setSortable( true );
	}

}