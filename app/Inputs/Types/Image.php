<?php

namespace Foundry\System\Inputs\Types;

use Foundry\Core\Inputs\Contracts\Field;
use Foundry\Core\Inputs\Types\Contracts\Inputable;
use Foundry\Core\Inputs\Types\FileInputType;

class Image extends FileInputType implements Field {

	/**
	 *
	 *
	 * @return Inputable|File
	 */
	static function input( ): Inputable {
		return (new static(
			'image',
			__('Image'),
			true
		))
            ->setType('image')
			->setPlaceholder(__('Click to browse for a image'))
			->addRule('exists:files,id')
			->setAction(resourceUri('foundry.system.files.upload.image'))
			->setDeleteUrl(resourceUri('foundry.system.files.delete'))
			;
	}

}
