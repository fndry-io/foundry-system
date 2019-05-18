<?php

namespace Plugins\Foundry\System\Models\Fields\Generic;

use Foundry\Models\Fields\Field;
use Illuminate\Database\Eloquent\Model;
use Foundry\Requests\Types\NumberInputType;
use Foundry\Requests\Types\Contracts\Inputable;

class Id extends NumberInputType implements Field {

	static function input( Model &$model = null ): Inputable {
		return ( new NumberInputType(
			'id',
			__( 'ID' ),
			true )
		)
			->isSortable( true );
	}

}