<?php

namespace Foundry\System\Inputs\User\Types;

use Foundry\Core\Inputs\Contracts\Field;

use Illuminate\Database\Eloquent\Model;
use Foundry\Core\Inputs\Types\PasswordInputType;
use Foundry\Core\Inputs\Types\InputType;
use Foundry\Core\Inputs\Types\Contracts\Inputable;

class Password extends PasswordInputType implements Field {

	/**
	 *
	 *
	 * @return Inputable|Password
	 */
	static function input( ): Inputable {
		return ( new static(
			'password',
			__( 'Password' ),
			true
		) )
			->setMax()
			->setSortable( false );
	}

}