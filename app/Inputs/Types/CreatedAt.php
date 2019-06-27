<?php

namespace Foundry\System\Inputs\Types;

use Foundry\Core\Inputs\Contracts\Field;
use Foundry\Core\Inputs\Types\Contracts\Inputable;
use Foundry\Core\Inputs\Types\DateTimeInputType;
use Foundry\Core\Entities\Entity;

class CreatedAt extends DateTimeInputType implements Field
{
	/**
	 * @param Entity|null $entity
	 *
	 * @return Inputable|CreatedAt
	 */
    static function input( Entity &$entity = null ): Inputable
    {
        return (new static(
            'created_at',
            __('Created At'),
            true
        ))
            ->setSortable(true);
    }

}