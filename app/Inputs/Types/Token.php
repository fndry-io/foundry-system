<?php

namespace Foundry\System\Inputs\Types;

use Foundry\Core\Inputs\Contracts\Field;

use Foundry\Core\Inputs\Types\TextInputType;
use Foundry\Core\Inputs\Types\Contracts\Inputable;

class Token extends TextInputType implements Field {

	/**
	 *
	 *
	 * @return Inputable|Token
	 */
	static function input( ): Inputable {
		return ( new static(
			'token',
			__( 'Token' ),
			true
		) )
			->setMax( 100 );
	}

}