<?php

namespace Foundry\System\Inputs\User\Types;

use Foundry\Core\Inputs\Contracts\Field;
use Foundry\Core\Entities\Entity;
use Foundry\Core\Inputs\Contracts\FieldOptions;
use Foundry\Core\Inputs\Types\ChoiceInputType;
use Foundry\Core\Inputs\Types\Contracts\Inputable;

class RememberMe extends ChoiceInputType implements Field, FieldOptions {

	/**
	 * @var string The default cast type for the value of this type
	 */
	protected $cast = 'boolean';

	/**
	 * @param Entity|null $entity
	 *
	 * @return Inputable|RememberMe
	 */
	static function input( Entity &$entity = null ): Inputable {
		return ( new static(
			'remember_me',
			__( 'Remember Me' ),
			false,
			static::options()
		) )
			->setHelp()
			->setSortable( false );
	}

	static function options( \Closure $closure = null, $value = null ): array {
		return [
			0 => __( 'No' ),
			1 => __( 'Yes' )
		];
	}

}