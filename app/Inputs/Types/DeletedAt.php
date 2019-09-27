<?php

namespace Foundry\System\Inputs\Types;

use Foundry\Core\Inputs\Contracts\Field;
use Foundry\Core\Inputs\Types\Contracts\Inputable;
use Foundry\Core\Inputs\Types\DateTimeInputType;


class DeletedAt extends DateTimeInputType implements Field
{
	/**
	 *
	 *
	 * @return Inputable|DeletedAt
	 */
    static function input( ): Inputable
    {
        return (new static(
            'deleted_at',
            __('Deleted At'),
	        false
        ))
            ->setSortable(true);
    }

}