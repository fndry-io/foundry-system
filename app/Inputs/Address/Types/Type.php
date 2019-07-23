<?php

namespace Foundry\System\Inputs\Address\Types;

use Foundry\Core\Inputs\Contracts\Field;
use Foundry\Core\Entities\Entity;
use Foundry\Core\Inputs\Contracts\FieldOptions;
use Foundry\Core\Inputs\Types\ChoiceInputType;
use Foundry\Core\Inputs\Types\Contracts\Inputable;

class Type extends ChoiceInputType implements Field, FieldOptions {

	/**
	 * @param Entity|null $entity
	 *
	 * @return Inputable|Type
	 */
	static function input( Entity &$entity = null ): Inputable {
		return ( new static(
			'type',
			__( 'Type' ),
			false,
			static::options()
		) )
			->setDefault('main')
			->setSortable( true );
	}

	/**
	 * The input options
	 *
	 * @param \Closure $closure A query builder to modify the query if needed
	 * @param mixed $value
	 *
	 * @return array
	 */
	static function options( \Closure $closure = null, $value = null ): array {
		return [
			"main" => "Main Office",
			"regional" => "Regional Office",
			"home" => "Home"
		];
	}
}