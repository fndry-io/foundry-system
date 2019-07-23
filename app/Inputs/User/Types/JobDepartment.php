<?php

namespace Foundry\System\Inputs\User\Types;

use Foundry\Core\Inputs\Contracts\Field;
use Foundry\Core\Entities\Entity;
use Foundry\Core\Inputs\Types\TextInputType;
use Foundry\Core\Inputs\Types\Contracts\Inputable;

class JobDepartment extends TextInputType implements Field {

	/**
	 * @param Entity|null $entity
	 *
	 * @return Inputable|JobDepartment
	 */
	static function input( Entity &$entity = null ): Inputable {
		return ( new static(
			'job_department',
			__( 'Job Division' ),
			false
		) )
			->setMax( 50 );
	}

}