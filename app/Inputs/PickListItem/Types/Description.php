<?php

namespace Foundry\System\Inputs\PickListItem\Types;

use Foundry\Core\Inputs\Contracts\Field;

use Foundry\Core\Inputs\Types\TextInputType;
use Foundry\Core\Inputs\Types\Contracts\Inputable;

class Description extends TextInputType implements Field {

	/**
	 *
	 *
	 * @return Inputable|Description
	 */
	static function input( ): Inputable {
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