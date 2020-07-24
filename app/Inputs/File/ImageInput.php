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
     * Create a file input from an uploaded file
     *
     * The value of the $resize can be one of the following:
     *  - `crop`: crop the image using the supplied width and height
     *  - `resize`: resize the image to the desired width and height (does not maintain aspect ratio)
     *  - `fit`: resize and crop the image to the best possible position using the supplied with and height
     *  - null: do not resize the image at all
     *
	 * @param UploadedFile $file The uploaded file
	 * @param array $inputs The inputs from the request
	 * @param boolean $is_public If the file is public
     * @param int $width The width to make the image based on the resize parameter
     * @param int $height The height to mage the image base on the resize parameter
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
			'size' => $file->getSize(),
			'is_public' => $is_public,
            'width' => $width,
            'height' => $height,
            'resize' => $resize,
		]);

		$input->setFile($file);
		return $input;
	}

}
