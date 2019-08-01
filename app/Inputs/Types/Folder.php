<?php

namespace Foundry\System\Inputs\Types;

use Foundry\Core\Inputs\Contracts\Field;
use Foundry\Core\Inputs\Types\Contracts\Inputable;
use Foundry\Core\Inputs\Types\NumberInputType;
use Foundry\Core\Entities\Entity;
use Illuminate\Validation\Rules\Exists;

class Folder extends NumberInputType implements Field {

	static function input( Entity &$entity = null ): Inputable {
		return ( new static(
			'folder',
			__( 'Folder' ),
			false )
		)
			->setRules(new Exists(\Foundry\System\Entities\Folder::class, 'id'))
			->setSortable( true );
	}

}