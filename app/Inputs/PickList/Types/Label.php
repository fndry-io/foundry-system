<?php

namespace Foundry\System\Inputs\PickList\Types;

use Foundry\Core\Inputs\Contracts\Field;
use Foundry\Core\Entities\Entity;
use Foundry\Core\Inputs\Types\TextInputType;
use Foundry\Core\Inputs\Types\Contracts\Inputable;

class Label extends TextInputType implements Field {

	/**
	 * @param Entity|null $entity
	 *
	 * @return Inputable|Label
	 */
	static function input( Entity &$entity = null ): Inputable {
		return ( new static(
			'label',
			__( 'Label' ),
			true
		) )
			->setMax( 50 )
			->setSortable( true );
	}

}