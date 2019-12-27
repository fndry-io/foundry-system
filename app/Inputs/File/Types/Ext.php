<?php

namespace Foundry\System\Inputs\File\Types;

use Foundry\Core\Inputs\Contracts\Field;

use Foundry\Core\Inputs\Types\TextInputType;
use Foundry\Core\Inputs\Types\Contracts\Inputable;

class Ext extends TextInputType implements Field {

	/**
	 * @return Inputable|Ext
	 */
	static function input( ): Inputable {
		return ( new static(
			'ext',
			__( 'Ext' ),
			true
		) )
			->setMax( 5 )
			->setSortable( true );
	}

}