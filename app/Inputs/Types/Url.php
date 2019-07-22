<?php

namespace Foundry\System\Inputs\Types;

use Foundry\Core\Inputs\Contracts\Field;
use Foundry\Core\Entities\Entity;
use Foundry\Core\Inputs\Types\Contracts\Inputable;
use Foundry\Core\Inputs\Types\TextInputType;

class Url extends TextInputType implements Field {

	/**
	 * @param Entity|null $entity
	 *
	 * @return Inputable|Email
	 */
	static function input( Entity &$entity = null ): Inputable {
		return ( new static(
			'url',
			__( 'Url' ),
			true
		) )
			->setPlaceholder('http://...')
			->addRule('url')
			->setHelp(__('Enter a valid URL starting with http:// or https://. E.G. http://www.google.com'))
			->setMax( 1024 );
	}

}