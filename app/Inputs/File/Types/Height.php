<?php

namespace Foundry\System\Inputs\File\Types;

use Foundry\Core\Inputs\Contracts\Field;

use Foundry\Core\Inputs\Types\NumberInputType;
use Foundry\Core\Inputs\Types\Contracts\Inputable;

class Height extends NumberInputType implements Field {

	/**
	 * @return Width
	 */
	static function input( ): Inputable {
		return ( new static(
			'height',
			__( 'Height' ),
			false
		) )
			;
	}

}
