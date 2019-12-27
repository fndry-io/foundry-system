<?php

namespace Foundry\System\Inputs\User\Types;

use Foundry\Core\Inputs\Contracts\Field;

use Illuminate\Database\Eloquent\Model;
use Foundry\Core\Inputs\Types\TextInputType;
use Foundry\Core\Inputs\Types\InputType;
use Foundry\Core\Inputs\Types\Contracts\Inputable;

class Username extends TextInputType implements Field {

	/**
	 *
	 *
	 * @return Inputable|Username
	 */
	static function input( ): Inputable {
		return ( new static(
			'username',
			__( 'Username' ),
			true
		) )
			->setRules('username')
			->setMax( 100 )
			->setSortable( true );
	}

}