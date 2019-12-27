<?php

namespace Foundry\System\Inputs\File\Types;

use Foundry\Core\Inputs\Contracts\Field;

use Foundry\Core\Inputs\Contracts\FieldOptions;
use Foundry\Core\Inputs\Types\ChoiceInputType;
use Foundry\Core\Inputs\Types\Contracts\Inputable;

class Resize extends ChoiceInputType implements Field, FieldOptions {

	/**
	 * @return Width
	 */
	static function input( ): Inputable {
		return ( new static(
			'resize',
			__( 'Resize' ),
			false,
            static::options()
		) )
            ;
	}

    /**
     * The input options
     *
     * @param \Closure $closure A query builder to modify the query if needed
     * @param mixed $value
     *
     * @return array
     */
    static function options(\Closure $closure = null, $value = null): array
    {
        return [
            [
                'text' => __('None'),
                'value' => ''
            ],
            [
                'text' => __('Resize'),
                'value' => 'resize'
            ],
            [
                'text' => __('Crop'),
                'value' => 'resize'
            ],
            [
                'text' => __('Fit'),
                'value' => 'resize'
            ]
        ];
    }
}
