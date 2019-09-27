<?php

namespace Foundry\System\Inputs\Types;

use Foundry\Core\Inputs\Contracts\Field;

use Foundry\Core\Inputs\Types\EmailInputType;
use Foundry\Core\Inputs\Types\Contracts\Inputable;

class Email extends EmailInputType implements Field {

	/**
	 *
	 *
	 * @return Inputable|Email
	 */
	static function input( ): Inputable {
		return ( new static(
			'email',
			__( 'Email' ),
			true
		) )
			->setMax( 100 );
	}

}