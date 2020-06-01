<?php

namespace Foundry\System\Inputs\File\Types;

use Foundry\Core\Inputs\Contracts\Field;

use Foundry\Core\Inputs\Contracts\FieldOptions;
use Foundry\Core\Inputs\Types\ChoiceInputType;
use Foundry\Core\Inputs\Types\Contracts\Inputable;

/**
 * Class Resize
 *
 * Controls the resize of the image
 *
 * The value of the input can be one of the following:
 *  - `crop`: crop the image using the supplied width and height
 *  - `resize`: resize the image to the desired width and height (does not maintain aspect ratio)
 *  - `fit`: resize and crop the image to the best possible position using the supplied with and height
 *  - null: do not resize the image at all
 *
 * @package Foundry\System\Inputs\File\Types
 */
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
    static function options(): array
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
