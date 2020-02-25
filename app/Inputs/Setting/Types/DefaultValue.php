<?php

namespace Foundry\System\Inputs\Setting\Types;

use Foundry\Core\Inputs\Contracts\Field;

use Foundry\Core\Inputs\Types\TextInputType;
use Foundry\Core\Inputs\Types\Contracts\Inputable;

class DefaultValue extends TextInputType implements Field {

	/**
	 *
	 *
	 * @return Inputable|DefaultValue
	 */
	static function input( ): Inputable {
		return ( new static(
			'default',
			__( 'Default' ),
			true
		) )
			->setMax( 100 )
			->setSortable( true );
	}

}
