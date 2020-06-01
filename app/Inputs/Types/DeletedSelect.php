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
            ->setEmpty(__('Not Deleted'));
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
                'value' => 'trashed',
                'text' => __('Trashed')
            ],
            [
                'value' => 'all',
                'text' => __('All')
            ]
        ];
    }
}
