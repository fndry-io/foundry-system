<?php

namespace Foundry\System\Inputs\User\Types;

use Foundry\Core\Inputs\Contracts\Field;
use Foundry\Core\Inputs\Contracts\FieldOptions;
use Foundry\System\Entities\Entity;
use Foundry\Core\Inputs\Types\ChoiceInputType;
use Foundry\Core\Inputs\Types\Contracts\Inputable;

class Timezone extends ChoiceInputType implements Field, FieldOptions {

	/**
	 * @param Entity|null $entity
	 *
	 * @return Inputable|Timezone
	 */
	static function input( Entity &$entity = null ): Inputable {
		return ( new static(
			'timezone',
			__( 'TimeZone' ),
			true,
			false,
			false,
			static::options()
		) )
			->setDefault("America/New_York")
			->setMax( 50 )
			->isSortable( false );
	}

	/**
	 * The input options
	 *
	 * @param \Closure $closure A query builder to modify the query if needed
	 * @param mixed $value
	 *
	 * @return array
	 */
	static function options( \Closure $closure = null, $value = null ): array
	{
		return array_combine( timezone_identifiers_list(), timezone_identifiers_list() );
	}
}