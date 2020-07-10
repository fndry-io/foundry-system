<?php

namespace Foundry\System\Inputs\Types;

use Foundry\Core\Inputs\Contracts\Field;
use Foundry\Core\Inputs\Types\Contracts\Inputable;
use Foundry\Core\Inputs\Types\FileInputType;

class Image extends FileInputType implements Field {

	/**
	 * @return Inputable|File
	 */
	static function input( ): Inputable {
		return (new static(
			'image',
			__('Image'),
			true
		))
            ->setType('image')
            ->setLayout('thumbnail')
			->setPlaceholder(__('Drag and Drop here or click to browse'))
			->addRule('file_exists')
			->setAction(resourceUri('system.files.upload.image'))
			->setDeleteUrl(resourceUri('system.files.delete'))
			;
	}


}
