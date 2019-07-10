<?php

namespace Foundry\System\Inputs\Types;

use Foundry\Core\Inputs\Contracts\Field;
use Foundry\Core\Inputs\Types\Contracts\Inputable;
use Foundry\Core\Inputs\Types\DateTimeInputType;
use Foundry\Core\Entities\Entity;

class UpdatedAt extends DateTimeInputType implements Field
{
	/**
	 * @param Entity|null $entity
	 *
	 * @return Inputable|UpdatedAt
	 */
    static function input( Entity &$entity = null ): Inputable
    {
        return (new static(
            'updated_at',
            __('Updated At'),
	        false
        ))
            ->setSortable(true);
    }

}