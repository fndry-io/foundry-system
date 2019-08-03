<?php

namespace Foundry\System\Inputs\PickList\Types;

use Foundry\Core\Inputs\Contracts\Field;
use Foundry\Core\Entities\Entity;
use Foundry\Core\Inputs\Types\CheckboxInputType;
use Foundry\Core\Inputs\Types\Contracts\Inputable;

class IsSystem extends CheckboxInputType implements Field {

	/**
	 * @param Entity|null $entity
	 *
	 * @return Inputable|IsSystem
	 */
	static function input( Entity &$entity = null ): Inputable {
		return ( new static(
			'status',
			__( 'Status' ),
			false
		) )
			->setReadonly(true)
			->setSortable( true );
	}



}