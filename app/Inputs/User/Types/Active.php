<?php

namespace Foundry\System\Inputs\User\Types;

use Foundry\Core\Inputs\Contracts\Field;
use Foundry\Core\Inputs\Contracts\FieldOptions;
use Foundry\Core\Inputs\Types\CheckboxInputType;
use Foundry\Core\Inputs\Types\Contracts\Inputable;
use Foundry\Core\Entities\Entity;

class Active extends CheckboxInputType implements Field {

	/**
	 * @param Entity|null $entity
	 *
	 * @return Inputable|Active
	 */
	static function input( Entity &$entity = null ): Inputable {
		return ( new static(
			'active',
			__( 'Access Enabled' ),
			false
		) )
			->setDefault(false)
			->setHelp(__('Enables or Disables the users access to the system.'))
			->setSortable( true );
	}

}