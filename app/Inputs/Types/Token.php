<?php

namespace Foundry\System\Inputs\Types;

use Foundry\Core\Inputs\Contracts\Field;
use Foundry\Core\Entities\Entity;
use Foundry\Core\Inputs\Types\TextInputType;
use Foundry\Core\Inputs\Types\Contracts\Inputable;

class Token extends TextInputType implements Field {

	/**
	 * @param Entity|null $entity
	 *
	 * @return Inputable|Token
	 */
	static function input( Entity &$entity = null ): Inputable {
		return ( new static(
			'token',
			__( 'Token' ),
			true
		) )
			->setMax( 100 );
	}

}