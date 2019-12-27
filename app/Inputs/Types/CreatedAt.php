<?php

namespace Foundry\System\Inputs\Types;

use Foundry\Core\Inputs\Contracts\Field;
use Foundry\Core\Inputs\Types\Contracts\Inputable;
use Foundry\Core\Inputs\Types\DateTimeInputType;


class CreatedAt extends DateTimeInputType implements Field
{
	/**
	 *
	 *
	 * @return Inputable|CreatedAt
	 */
    static function input( ): Inputable
    {
        return (new static(
            'created_at',
            __('Created At'),
            true
        ))
            ->setSortable(true);
    }

}