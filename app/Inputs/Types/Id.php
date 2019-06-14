<?php

namespace Plugins\Foundry\System\Models\Fields\Generic;

use Foundry\Core\Inputs\Contracts\Field;
use Foundry\Core\Inputs\Types\Contracts\Inputable;
use Foundry\Core\Inputs\Types\NumberInputType;
use Foundry\System\Entities\Entity;

class Id extends NumberInputType implements Field {

	static function input( Entity &$entity = null ): Inputable {
		return ( new static(
			'id',
			__( 'ID' ),
			true )
		)
			->setSortable( true );
	}

}