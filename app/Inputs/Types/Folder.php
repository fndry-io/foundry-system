<?php

namespace Foundry\System\Inputs\Types;

use Foundry\Core\Inputs\Contracts\Field;
use Foundry\Core\Inputs\Types\Contracts\Inputable;
use Foundry\Core\Inputs\Types\NumberInputType;

class Folder extends NumberInputType implements Field {

	static function input( ): Inputable {
		return ( new static(
			'folder',
			__( 'Folder' ),
			false )
		)
			->addRule('exists:system_folders,id')
			;
	}

}
