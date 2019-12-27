<?php

namespace Foundry\System\Inputs\User\Types;

use Foundry\Core\Inputs\Contracts\Field;

use Foundry\Core\Inputs\Types\TextInputType;
use Foundry\Core\Inputs\Types\Contracts\Inputable;

class JobTitle extends TextInputType implements Field {

	/**
	 *
	 *
	 * @return Inputable|JobTitle
	 */
	static function input( ): Inputable {
		return ( new static(
			'job_title',
			__( 'Job Title' ),
			false
		) )
			->setMax( 50 );
	}

}