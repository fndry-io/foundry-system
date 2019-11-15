<?php

namespace Foundry\System\Inputs\Types;

use Foundry\Core\Inputs\Contracts\Field;
use Foundry\Core\Inputs\Contracts\FieldOptions;
use Foundry\Core\Inputs\Types\ChoiceInputType;
use Foundry\Core\Inputs\Types\Contracts\Inputable;

class DeletedSelect extends ChoiceInputType implements Field, FieldOptions
{

    protected $cast = 'string';

	/**
	 * @return Inputable|DeletedAt
	 */
    static function input( ): Inputable
    {
        return (new static(
            'deleted',
            __('Deleted'),
	        false,
            static::options()
        ))
            ->setDefault(0);
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
                'value' => '-1',
                'text' => 'All'
            ],
            [
                'value' => '1',
                'text' => 'Deleted'
            ],
            [
                'value' => '0',
                'text' => 'Not Deleted'
            ]
        ];
    }
}
