<?php

namespace Foundry\System\Inputs\File\Types;

use Foundry\Core\Inputs\Contracts\Field;
use Foundry\Core\Entities\Entity;
use Foundry\Core\Inputs\Types\NumberInputType;
use Foundry\Core\Inputs\Types\TextInputType;
use Foundry\Core\Inputs\Types\Contracts\Inputable;

class Size extends NumberInputType implements Field {

	/**
	 * @param Entity|null $entity
	 *
	 * @return Inputable|Size
	 */
	static function input( Entity &$entity = null ): Inputable {
		return ( new static(
			'size',
			__( 'Size' ),
			true
		) )
			->setDecimals(2)
			->setSortable( true );
	}

}