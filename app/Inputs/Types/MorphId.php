<?php

namespace Foundry\System\Inputs\Types;

use Foundry\Core\Inputs\Contracts\Field;
use Foundry\Core\Inputs\Types\Contracts\Inputable;
use Foundry\Core\Inputs\Types\NumberInputType;
use Foundry\Core\Entities\Entity;

class MorphId extends NumberInputType implements Field {

	static function input( Entity &$entity = null ): Inputable {
		return ( new static(
			'morph_id',
			__( 'Morph Id' ),
			true )
		)
			->setSortable( true );
	}

}