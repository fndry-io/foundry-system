<?php

namespace Foundry\System\Inputs\File\Types;

use Foundry\Core\Inputs\Contracts\Field;

use Foundry\Core\Inputs\Types\TextInputType;
use Foundry\Core\Inputs\Types\Contracts\Inputable;

class OriginalName extends TextInputType implements Field {

	/**
	 *
	 *
	 * @return Inputable|OriginalName
	 */
	static function input( ): Inputable {
		return ( new static(
			'original_name',
			__( 'Original Name' ),
			true
		) )
			->setMax( 255 )
			->setSortable( true );
	}

}