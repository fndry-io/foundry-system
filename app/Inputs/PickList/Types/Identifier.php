<?php

namespace Foundry\System\Inputs\PickList\Types;

use Foundry\Core\Inputs\Contracts\Field;

use Foundry\Core\Inputs\Types\TextInputType;
use Foundry\Core\Inputs\Types\Contracts\Inputable;

class Identifier extends TextInputType implements Field {

	/**
	 *
	 *
	 * @return Inputable|Identifier
	 */
	static function input( ): Inputable {
		return ( new static(
			'identifier',
			__( 'Identifier' ),
			true
		) )
			->setMax( 100 )
			->setReadonly(true)
			->setSortable( true );
	}

}