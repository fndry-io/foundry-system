<?php

namespace Foundry\System\Inputs\Types;

use Foundry\Core\Inputs\Contracts\Field;
use Foundry\Core\Inputs\Types\Contracts\Inputable;
use Foundry\Core\Inputs\Types\NumberInputType;
use Illuminate\Validation\Rules\Exists;

class Folder extends NumberInputType implements Field {

	static function input( ): Inputable {
		return ( new static(
			'folder',
			__( 'Folder' ),
			false )
		)
			->setRules(new Exists('folders', 'id'))
			->setSortable( true );
	}

}