<?php

namespace Foundry\System\Inputs\User\Types;

use Foundry\Core\Inputs\Contracts\Field;
use Foundry\Core\Inputs\Types\EmailInputType;
use Foundry\System\Entities\Entity;
use Foundry\Core\Inputs\Types\TextInputType;
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
			->addRule('email')
			->setMax( 100 )
			->setSortable( true );
	}

}