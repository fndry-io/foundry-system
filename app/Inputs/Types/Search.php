<?php

namespace Foundry\System\Inputs\Types;

use Foundry\Core\Inputs\Contracts\Field;

use Foundry\Core\Inputs\Types\Contracts\Inputable;
use Foundry\Core\Inputs\Types\TextInputType;

class Search extends TextInputType implements Field {

	/**
	 * @return self
	 */
	static function input( ): Inputable {
		return ( new static(
			'search',
			__( 'Search' ),
			false
		) );
	}

}
