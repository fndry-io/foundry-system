<?php

namespace Foundry\System\Inputs\PickListItem\Types;

use Foundry\Core\Inputs\Contracts\Field;

use Foundry\Core\Inputs\Types\TextInputType;
use Foundry\Core\Inputs\Types\Contracts\Inputable;

class Identifier extends TextInputType implements Field {

	/**
	 *
	 *
	 * @return Inputable|Name
	 */
	static function input( ): Inputable {
		return ( new static(
			'identifier',
			__( 'Identifier' ),
			true
		) )
			->setReadonly(true)
			->setMax( 100 )
			->setSortable( true );
	}

}