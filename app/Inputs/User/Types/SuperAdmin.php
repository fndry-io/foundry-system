<?php

namespace Foundry\System\Inputs\User\Types;

use Foundry\Core\Inputs\Contracts\Field;
use Foundry\Core\Entities\Entity;
use Foundry\Core\Inputs\Types\CheckboxInputType;
use Foundry\Core\Inputs\Types\Contracts\Inputable;

class SuperAdmin extends CheckboxInputType implements Field {

	/**
	 * @param Entity|null $entity
	 *
	 * @return Inputable|SuperAdmin
	 */
	static function input( Entity &$entity = null ): Inputable {
		return ( new static(
			'super_admin',
			__( 'Super Admin' ),
			false
		) )
			->setDefault(false)
			->setHelp(__('Makes the user have Super Admin / All access to the system.'));
	}

}