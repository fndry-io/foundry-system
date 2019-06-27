<?php

namespace Foundry\System\Inputs\Types;

use Foundry\Core\Inputs\Contracts\Field;
use Foundry\Core\Inputs\Types\Contracts\Inputable;
use Foundry\Core\Inputs\Types\DateTimeInputType;
use Foundry\Core\Entities\Entity;

class DeletedAt extends DateTimeInputType implements Field
{
	/**
	 * @param Entity|null $entity
	 *
	 * @return Inputable|DeletedAt
	 */
    static function input( Entity &$entity = null ): Inputable
    {
        return (new static(
            'deleted_at',
            __('Deleted At'),
            true
        ))
            ->setSortable(true);
    }

}