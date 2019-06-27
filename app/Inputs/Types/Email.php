<?php

namespace Foundry\System\Inputs\Types;

use Foundry\Core\Inputs\Contracts\Field;
use Foundry\Core\Entities\Entity;
use Foundry\Core\Inputs\Types\EmailInputType;
use Foundry\Core\Inputs\Types\Contracts\Inputable;

class Email extends EmailInputType implements Field {

	/**
	 * @param Entity|null $entity
	 *
	 * @return Inputable|Email
	 */
	static function input( Entity &$entity = null ): Inputable {
		return ( new static(
			'email',
			__( 'Email' ),
			true
		) )
			->setMax( 100 );
	}

}