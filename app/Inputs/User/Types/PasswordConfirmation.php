<?php

namespace Foundry\System\Inputs\User\Types;

use Foundry\Core\Inputs\Contracts\Field;

use Illuminate\Database\Eloquent\Model;
use Foundry\Core\Inputs\Types\Contracts\Inputable;
use Foundry\Core\Inputs\Types\PasswordInputType;
use Foundry\Core\Inputs\Types\TextInputType;
use Foundry\Core\Inputs\Types\InputType;

class PasswordConfirmation extends PasswordInputType implements Field {

	/**
	 *
	 *
	 * @return Inputable|PasswordConfirmation
	 */
	static function input( ): Inputable {
		return ( new static(
			'password_confirmation',
			__( 'Confirm Password' ),
			true
		) )
			->setMax( 255 );
	}

}