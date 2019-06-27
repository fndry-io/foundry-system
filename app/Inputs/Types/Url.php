<?php

namespace Foundry\System\Inputs\Types;

use Foundry\Core\Inputs\Contracts\Field;
use Foundry\Core\Entities\Entity;
use Foundry\Core\Inputs\Types\Contracts\Inputable;
use Foundry\Core\Inputs\Types\TextInputType;

class Url extends TextInputType implements Field {

	/**
	 * @param Entity|null $entity
	 *
	 * @return Inputable|Email
	 */
	static function input( Entity &$entity = null ): Inputable {
		return ( new static(
			'url',
			__( 'Url' ),
			true
		) )
			->addRule('url')
			->setMax( 1024 );
	}

}