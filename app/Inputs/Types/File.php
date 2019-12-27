<?php

namespace Foundry\System\Inputs\Types;


use Foundry\Core\Inputs\Contracts\Field;
use Foundry\Core\Inputs\Types\Contracts\Inputable;
use Foundry\Core\Inputs\Types\FileInputType;
use Foundry\System\Http\Requests\Files\UploadFileRequest;

class File extends FileInputType implements Field {

	/**
	 *
	 *
	 * @return Inputable|File
	 */
	static function input( ): Inputable {
		return (new static(
			'file',
			__('File'),
			true
		))
			->setPlaceholder(__('Drag and Drop here or click to browse'))
			->addRule('file')
            ->setHelp(__('File type should be one of: :types and no bigger than :size', ['types' => implode(', ', UploadFileRequest::fileTypes()), 'size' => number_format(UploadFileRequest::fileSize() / 1000) . 'MB']))
			->setAction(resourceUri('foundry.system.files.upload'))
			->setDeleteUrl(resourceUri('foundry.system.files.delete'))
			;
	}

}
