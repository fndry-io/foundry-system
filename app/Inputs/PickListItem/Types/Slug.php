<?php

namespace Foundry\System\Inputs\PickListItem\Types;

use Foundry\Core\Inputs\Contracts\Field;
use Foundry\Core\Entities\Entity;
use Foundry\Core\Inputs\Types\TextInputType;
use Foundry\Core\Inputs\Types\Contracts\Inputable;

class Slug extends TextInputType implements Field {

	/**
	 * @param Entity|null $entity
	 *
	 * @return Inputable|Name
	 */
	static function input( Entity &$entity = null ): Inputable {
		return ( new static(
			'slug',
			__( 'Slug' ),
			true
		) )
			->setReadonly(true)
			->setMax( 100 )
			->setSortable( true );
	}

}