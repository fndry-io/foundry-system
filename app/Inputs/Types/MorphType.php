<?php

namespace Foundry\System\Inputs\Types;

use Foundry\Core\Inputs\Contracts\Field;
use Foundry\Core\Inputs\Types\Contracts\Inputable;

use Foundry\Core\Inputs\Types\TextInputType;

class MorphType extends TextInputType implements Field {

	static function input( ): Inputable {
		return ( new static(
			'morph_type',
			__( 'Morph Type' ),
			true )
		)
			->setMax(255)
			->setSortable( true );
	}

}