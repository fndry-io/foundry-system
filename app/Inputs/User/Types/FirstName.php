<?php

namespace Foundry\System\Inputs\User\Types;

use Foundry\Core\Inputs\Contracts\Field;
use Foundry\System\Entities\Entity;
use Illuminate\Database\Eloquent\Model;
use Foundry\Core\Inputs\Types\TextInputType;
use Foundry\Core\Inputs\Types\InputType;
use Foundry\Core\Inputs\Types\Contracts\Inputable;

class FirstName extends TextInputType implements Field {

	/**
	 * @param Entity|null $entity
	 *
	 * @return Inputable|FirstName
	 */
	static function input( Entity &$entity = null ): Inputable {
		return ( new static(
			'first_name',
			__( 'First Name' ),
			true
		) )
			->setMax( 100 )
			->isSortable( true );
	}

}