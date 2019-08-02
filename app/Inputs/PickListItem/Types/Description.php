<?php

namespace Foundry\System\Inputs\PickListItem\Types;

use Foundry\Core\Inputs\Contracts\Field;
use Foundry\Core\Entities\Entity;
use Foundry\Core\Inputs\Types\TextInputType;
use Foundry\Core\Inputs\Types\Contracts\Inputable;

class Description extends TextInputType implements Field {

	/**
	 * @param Entity|null $entity
	 *
	 * @return Inputable|Description
	 */
	static function input( Entity &$entity = null ): Inputable {
		return ( new static(
			'description',
			__( 'Description' ),
			false
		) )
			->setMax( 1024 )
			->setMultiline(5)
			;
	}

}