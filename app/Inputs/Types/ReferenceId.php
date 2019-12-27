<?php

namespace Foundry\System\Inputs\Types;

use Foundry\Core\Inputs\Contracts\Field;
use Foundry\Core\Inputs\Types\Contracts\Inputable;
use Foundry\Core\Inputs\Types\HiddenInputType;


class ReferenceId extends HiddenInputType implements Field {

	protected $cast = 'int';

	static function input( ): Inputable {
		return ( new static(
			'reference_id',
			__( 'Reference Id' ),
			false )
		);
	}

}