<?php

namespace Foundry\System\Inputs\User\Types;

use Foundry\Core\Inputs\Contracts\Field;
use Foundry\Core\Inputs\Contracts\FieldOptions;
use Foundry\Core\Inputs\Types\CheckboxInputType;
use Foundry\Core\Inputs\Types\Contracts\Inputable;


class Active extends CheckboxInputType implements Field {

	/**
	 *
	 *
	 * @return Inputable|Active
	 */
	static function input( ): Inputable {
		return ( new static(
			'active',
			__( 'Access Enabled' ),
			false
		) )
			->setDefault(true)
			->setHelp(__('Enables or Disables the users access to the system.'))
			->setSortable( true );
	}

}