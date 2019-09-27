<?php

namespace Foundry\System\Inputs\PickListItem\Types;

use Foundry\Core\Inputs\Contracts\Field;
use Foundry\Core\Inputs\Types\CheckboxInputType;
use Foundry\Core\Inputs\Types\Contracts\Inputable;


class DefaultItem extends CheckboxInputType implements Field {

	static function input( ): Inputable {
		return ( new static(
			'default_item',
			__( 'Is Default' ),
			false )
		)
			->setHelp(__('Determines if this item is the default selected option when the pick list is displayed.'))
			->setSortable( true )
            ->setDefault(false)
            ;
	}

}