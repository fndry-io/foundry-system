<?php

namespace Foundry\System\Inputs\Types;

use Foundry\Core\Inputs\Contracts\Field;
use Foundry\Core\Inputs\Types\Contracts\Inputable;
use Foundry\Core\Inputs\Types\DateTimeInputType;


class UpdatedAt extends DateTimeInputType implements Field
{
	/**
	 *
	 *
	 * @return Inputable|UpdatedAt
	 */
    static function input( ): Inputable
    {
        return (new static(
            'updated_at',
            __('Updated At'),
	        false
        ))
            ->setSortable(true);
    }

}