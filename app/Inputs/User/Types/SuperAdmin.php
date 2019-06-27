<?php

namespace Foundry\System\Inputs\User\Types;

use Foundry\Core\Inputs\Contracts\Field;
use Foundry\Core\Entities\Entity;
use Illuminate\Database\Eloquent\Model;
use Foundry\Core\Inputs\Contracts\FieldOptions;
use Foundry\Core\Inputs\Types\ChoiceInputType;
use Foundry\Core\Inputs\Types\InputType;
use Foundry\Core\Inputs\Types\Contracts\Inputable;

class SuperAdmin extends ChoiceInputType implements Field, FieldOptions {

	/**
	 * @param Entity|null $entity
	 *
	 * @return Inputable|SuperAdmin
	 */
	static function input( Entity &$entity = null ): Inputable {
		return ( new static(
			'is_super_admin',
			__( 'Is Super Admin' ),
			true,
			static::options()
		) )
			->setExpanded(true)
			->setSortable( false );
	}

	static function options( \Closure $closure = null, $value = null ): array {
		return [
			0 => __( 'No' ),
			1 => __( 'Yes' )
		];
	}

}