<?php

namespace Foundry\System\Inputs\PickListItem\Types;

use Foundry\Core\Inputs\Contracts\Field;
use Foundry\Core\Entities\Entity;
use Foundry\Core\Inputs\Contracts\FieldOptions;
use Foundry\Core\Inputs\Types\CheckboxInputType;
use Foundry\Core\Inputs\Types\ChoiceInputType;
use Foundry\Core\Inputs\Types\Contracts\Inputable;

class Status extends CheckboxInputType implements Field {

    protected $cast = 'boolean';

    /**
     * @param Entity|null $entity
     *
     * @return Inputable|Sequence
     */
    static function input( Entity &$entity = null ): Inputable {
        return ( new static(
            'status',
            __( 'Status' ),
            false
        ) )
            ->setSortable( true );
    }



}

