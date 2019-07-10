<?php

namespace Foundry\System\Inputs\Types;

use Foundry\Core\Inputs\Contracts\Field;
use Foundry\Core\Inputs\Types\Contracts\Inputable;
use Foundry\Core\Inputs\Types\DateTimeInputType;
use Foundry\Core\Entities\Entity;

class DateAt extends DateTimeInputType implements Field
{
	/**
	 * @param Entity|null $entity
	 *
	 * @return Inputable|DateAt
	 */
    static function input( Entity &$entity = null ): Inputable
    {
        return (new static(
            'date_at',
            __('Date At'),
            false
        ))
            ->setSortable(true);
    }

}