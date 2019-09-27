<?php

namespace Foundry\System\Inputs\Types;

use Foundry\Core\Inputs\Contracts\Field;
use Foundry\Core\Inputs\Types\Contracts\Inputable;
use Foundry\Core\Inputs\Types\NumberInputType;

use Illuminate\Validation\Rules\Exists;

class Node extends NumberInputType implements Field {

	static function input( ): Inputable {
		return ( new static(
			'node',
			__( 'Node' ),
			false )
		)
			->setRules(new Exists(\Foundry\System\Entities\Node::class, 'id'))
			->setSortable( true );
	}

}