<?php

namespace Foundry\System\Inputs\PickListItem\Types;

use Foundry\Core\Inputs\Contracts\Field;

use Foundry\Core\Inputs\Types\CheckboxInputType;
use Foundry\Core\Inputs\Types\Contracts\Inputable;

class Status extends CheckboxInputType implements Field {

    protected $cast = 'boolean';

    /**
     *
     *
     * @return Inputable|Sequence
     */
    static function input( ): Inputable {
        return ( new static(
            'status',
            __( 'Selectable' ),
            false
        ) )
	        ->setDefault(true)
	        ->setHelp(__('Controls if this item is displayed and selectable.'))
            ->setSortable( true );
    }



}

