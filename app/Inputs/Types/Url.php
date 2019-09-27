<?php

namespace Foundry\System\Inputs\Types;

use Foundry\Core\Inputs\Contracts\Field;

use Foundry\Core\Inputs\Types\Contracts\Inputable;
use Foundry\Core\Inputs\Types\TextInputType;

class Url extends TextInputType implements Field {

	/**
	 *
	 *
	 * @return Inputable|Email
	 */
	static function input( ): Inputable {
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