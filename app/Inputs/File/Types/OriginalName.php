<?php

namespace Foundry\System\Inputs\File\Types;

use Foundry\Core\Inputs\Contracts\Field;
use Foundry\Core\Entities\Entity;
use Foundry\Core\Inputs\Types\TextInputType;
use Foundry\Core\Inputs\Types\Contracts\Inputable;

class OriginalName extends TextInputType implements Field {

	/**
	 * @param Entity|null $entity
	 *
	 * @return Inputable|OriginalName
	 */
	static function input( Entity &$entity = null ): Inputable {
		return ( new static(
			'original_name',
			__( 'Original Name' ),
			true
		) )
			->setMax( 255 )
			->setSortable( true );
	}

}