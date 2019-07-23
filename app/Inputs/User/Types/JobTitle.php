<?php

namespace Foundry\System\Inputs\User\Types;

use Foundry\Core\Inputs\Contracts\Field;
use Foundry\Core\Entities\Entity;
use Foundry\Core\Inputs\Types\TextInputType;
use Foundry\Core\Inputs\Types\Contracts\Inputable;

class JobTitle extends TextInputType implements Field {

	/**
	 * @param Entity|null $entity
	 *
	 * @return Inputable|JobTitle
	 */
	static function input( Entity &$entity = null ): Inputable {
		return ( new static(
			'job_title',
			__( 'Job Title' ),
			false
		) )
			->setMax( 50 );
	}

}