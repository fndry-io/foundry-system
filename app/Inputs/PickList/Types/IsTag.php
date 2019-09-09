<?php

namespace Foundry\System\Inputs\PickList\Types;

use Foundry\Core\Inputs\Contracts\Field;
use Foundry\Core\Entities\Entity;
use Foundry\Core\Inputs\Types\CheckboxInputType;
use Foundry\Core\Inputs\Types\Contracts\Inputable;

class IsTag extends CheckboxInputType implements Field {

	/**
	 * @param Entity|null $entity
	 *
	 * @return Inputable|IsTag
	 */
	static function input( Entity &$entity = null ): Inputable {
		return ( new static(
			'is_tag',
			__( 'Is Tag' ),
			false
		) )
			->setHelp(__('Controls if this pick list can be used like a tag list in the UI and users'))
			->setReadonly(true)
			->setSortable( true );
	}



}