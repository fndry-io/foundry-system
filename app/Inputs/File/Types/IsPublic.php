<?php

namespace Foundry\System\Inputs\File\Types;


use Foundry\Core\Inputs\Contracts\Field;
use Foundry\Core\Inputs\Types\CheckboxInputType;
use Foundry\Core\Inputs\Types\Contracts\Inputable;

class IsPublic extends CheckboxInputType implements Field {

	/**
	 *
	 *
	 * @return Inputable|IsPublic
	 */
	public static function input( ): Inputable {
		return (new static(
			'is_public',
			__('Is Public'),
			false
		))
			->setDefault(false)
			;
	}

}