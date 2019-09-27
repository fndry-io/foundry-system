<?php

namespace Foundry\System\Inputs\Types;

use Foundry\Core\Inputs\Contracts\Field;
use Foundry\Core\Inputs\Types\Contracts\Inputable;
use Foundry\Core\Inputs\Types\NumberInputType;


class MorphId extends NumberInputType implements Field {

	static function input( ): Inputable {
		return ( new static(
			'morph_id',
			__( 'Morph Id' ),
			true )
		)
			->setSortable( true );
	}

}