<?php

namespace Foundry\System\Inputs\Types;

use Foundry\Core\Inputs\Contracts\Field;
use Foundry\Core\Inputs\Types\Contracts\Inputable;

use Foundry\Core\Inputs\Types\HiddenInputType;

class ReferenceType extends HiddenInputType implements Field {

	static function input( ): Inputable {
		return ( new static(
			'reference_type',
			__( 'Reference Type' ),
			false )
		);
	}

}