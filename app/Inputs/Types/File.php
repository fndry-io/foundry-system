<?php

namespace Foundry\System\Inputs\Types;

use Foundry\Core\Entities\Entity;
use Foundry\Core\Inputs\Contracts\Field;
use Foundry\Core\Inputs\Types\Contracts\Inputable;
use Foundry\Core\Inputs\Types\FileInputType;

class File extends FileInputType implements Field {

	/**
	 * @param Entity|null $entity
	 *
	 * @return Inputable
	 */
	static function input( Entity &$entity = null ): Inputable {
		return (new static(
			'file',
			__('File'),
			true
		))
			//->addRule('exists:files,id')
			->setAction(routeUri('foundry.system.files.upload'))
			->setDeleteUrl(routeUri('foundry.system.files.delete'))
			;
	}

}
