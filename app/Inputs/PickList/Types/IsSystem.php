<?php

namespace Foundry\System\Inputs\PickList\Types;

use Foundry\Core\Inputs\Contracts\Field;

use Foundry\Core\Inputs\Types\CheckboxInputType;
use Foundry\Core\Inputs\Types\Contracts\Inputable;

class IsSystem extends CheckboxInputType implements Field {

	/**
	 *
	 *
	 * @return Inputable|IsSystem
	 */
	static function input( ): Inputable {
		return ( new static(
			'is_system',
			__( 'Is System PickList' ),
			false
		) )
			->setHelp(__('System Pick Lists cannot be modified'))
			->setReadonly(true)
			->setSortable( true );
	}



}