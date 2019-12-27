<?php

namespace Foundry\System\Inputs\PickListItem\Types;

use Foundry\Core\Inputs\Contracts\Field;

use Foundry\Core\Inputs\Types\Contracts\Inputable;
use Foundry\Core\Inputs\Types\NumberInputType;

class Sequence extends NumberInputType implements Field {

	/**
	 *
	 *
	 * @return Inputable|Sequence
	 */
	static function input( ): Inputable {
		return ( new static(
			'sequence',
			__( 'Sequence' ),
			true
		) )
			->setDefault(0)
			->setHelp(__('Controls the sequence this item will be displayed at. Ordering is done by sequence lowest to highest and then name from A-Z.'))
			->setSortable( true );
	}

}