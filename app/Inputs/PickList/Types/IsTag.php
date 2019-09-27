<?php

namespace Foundry\System\Inputs\PickList\Types;

use Foundry\Core\Inputs\Contracts\Field;

use Foundry\Core\Inputs\Types\CheckboxInputType;
use Foundry\Core\Inputs\Types\Contracts\Inputable;

class IsTag extends CheckboxInputType implements Field {

	/**
	 *
	 *
	 * @return Inputable|IsTag
	 */
	static function input( ): Inputable {
		return ( new static(
			'is_tag',
			__( 'Is Tag' ),
			false
		) )
			->setHelp(__("Set's this pick list as taggable allowing the user to dynamically add new entries."))
			->setReadonly(true)
			->setSortable( true );
	}



}