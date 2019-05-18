<?php

namespace Foundry\System\Inputs\User\Types;

use Foundry\Core\Inputs\Contracts\Field;
use Foundry\Core\Inputs\Contracts\FieldOptions;
use Foundry\Core\Inputs\Types\ChoiceInputType;
use Foundry\Core\Inputs\Types\Contracts\Inputable;
use Foundry\System\Entities\Entity;

class Active extends ChoiceInputType implements Field, FieldOptions {

	/**
	 * @param Entity|null $entity
	 *
	 * @return Inputable|Active
	 */
	static function input( Entity &$entity = null ): Inputable {
		return ( new static(
			'active',
			__( 'Active' ),
			true,
			false,
			false,
			Active::options()
		) )
			->setHelp()
			->isSortable( true );
	}

	static function options( \Closure $closure = null, $value = null ): array {
		return [
			0 => __( 'No' ),
			1 => __( 'Yes' )
		];
	}

}