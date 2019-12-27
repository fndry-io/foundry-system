<?php

namespace Foundry\System\Inputs\File\Types;

use Foundry\Core\Inputs\Contracts\Field;

use Foundry\Core\Inputs\Types\TextInputType;
use Foundry\Core\Inputs\Types\Contracts\Inputable;

class Type extends TextInputType implements Field {

	/**
	 *
	 *
	 * @return Inputable|Type
	 */
	static function input( ): Inputable {
		return ( new static(
			'type',
			__( 'Type' ),
			true
		) )
			->setMax( 255 )
			->setSortable( true );
	}

}