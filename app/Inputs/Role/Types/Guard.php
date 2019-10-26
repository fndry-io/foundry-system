<?php

namespace Foundry\System\Inputs\Role\Types;


use Foundry\Core\Inputs\Contracts\Field;
use Foundry\Core\Inputs\Contracts\FieldOptions;
use Foundry\Core\Inputs\Types\ChoiceInputType;
use Foundry\Core\Inputs\Types\Contracts\Inputable;

class Guard extends ChoiceInputType implements Field, FieldOptions {

	/**
	 * @return $this
	 */
	static function input(): Inputable {
		return ( new static(
			'guard_name',
			__( 'Guard' ),
			true,
            static::options()
		) )
            ->setDefault('system')
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
		    [
		        'text' => __('System Management'),
                'value' => 'system'
            ],
            [
                'text' => __('Test Guard'),
                'value' => 'test'
            ]
		];
	}
}
