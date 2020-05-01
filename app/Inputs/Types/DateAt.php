<?php

namespace Foundry\System\Inputs\Types;

use Foundry\Core\Inputs\Contracts\Field;
use Foundry\Core\Inputs\Types\Contracts\Inputable;
use Foundry\Core\Inputs\Types\DateTimeInputType;


class DateAt extends DateTimeInputType implements Field
{
	/**
	 *
	 *
	 * @return Inputable|DateAt
	 */
    static function input( ): Inputable
    {
        return (new static(
            'date_at',
            __('Date At'),
            false
        ))
        ;
    }

}
