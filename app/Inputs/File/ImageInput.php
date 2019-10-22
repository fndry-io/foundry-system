<?php

namespace Foundry\System\Inputs\File;

use Foundry\Core\Support\InputTypeCollection;
use Foundry\System\Inputs\File\Types\Ext;
use Foundry\System\Inputs\File\Types\Height;
use Foundry\System\Inputs\File\Types\Resize;
use Foundry\System\Inputs\File\Types\Size;
use Foundry\System\Inputs\File\Types\Type;
use Foundry\System\Inputs\File\Types\IsPublic;
use Foundry\System\Inputs\File\Types\Width;
use Foundry\System\Inputs\Types\Folder;
use Foundry\System\Inputs\Types\ReferenceId;
use Foundry\System\Inputs\Types\ReferenceType;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;

/**
 * Class ImageInput
 *
 * @package Foundry\System\Inputs
 *
 */
class ImageInput extends FileInput {

	public function types() : InputTypeCollection
	{
		return InputTypeCollection::fromTypes([
			Folder::input(),
			ReferenceType::input(),
			ReferenceId::input(),
			Type::input(),
			Ext::input(),
			Size::input(),
			IsPublic::input(),
            Width::input(),
            Height::input(),
            Resize::input()
		]);
	}

	/**
	 * @param UploadedFile $file The uploaded file
	 * @param array $inputs The inputs from the request
	 * @param boolean $is_public If the file is public
     * @param int $width
     * @param int $height
     * @param string $resize
	 *
	 * @return self
	 */
	static function fromUploadedFile(UploadedFile $file, array $inputs = [], $is_public = false, $width = null, $height = null, $resize = null){
		$input = new static([
			'folder' => Arr::get($inputs, 'folder'),
			'reference_type' => Arr::get($inputs, 'reference_type'),
			'reference_id' => Arr::get($inputs, 'reference_id'),
			'type' => $file->getMimeType(),
			'ext'  => $file->getClientOriginalExtension(),
			'size' => round($file->getSize() / 1000, 2),
			'is_public' => $is_public,
            'width' => $width,
            'height' => $height,
            'resize' => $resize,
		]);

		$input->setFile($file);
		return $input;
	}

}
