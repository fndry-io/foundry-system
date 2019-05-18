<?php

namespace Plugins\Foundry\System\Models\Fields\Generic;

use Foundry\Core\Inputs\Contracts\Field;
use Foundry\Core\Inputs\Types\Contracts\Inputable;
use Foundry\Core\Inputs\Types\DateTimeInputType;
use Foundry\System\Entities\Entity;

class UpdatedAt extends DateTimeInputType implements Field
{
	/**
	 * @param Entity|null $entity
	 *
	 * @return Inputable|CreatedAt
	 */
    static function input( Entity &$entity = null ): Inputable
    {
        return (new static(
            'updated_at',
            __('Updated At'),
            true
        ))
            ->isSortable(true);
    }

}