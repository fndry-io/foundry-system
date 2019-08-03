<?php

namespace Foundry\System\Inputs\File;

use Foundry\Core\Inputs\Inputs;
use Foundry\Core\Inputs\Types\Traits\HasMultiple;
use Foundry\Core\Support\InputTypeCollection;
use Foundry\System\Inputs\File\Types\Ext;
use Foundry\System\Inputs\File\Types\Size;
use Foundry\System\Inputs\File\Types\Type;
use Foundry\System\Inputs\File\Types\IsPublic;
use Foundry\System\Inputs\Types\Folder;
use Foundry\System\Inputs\Types\ReferenceId;
use Foundry\System\Inputs\Types\ReferenceType;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;

/**
 * Class AddressInput
 *
 * @package Foundry\System\Inputs
 *
 */
class FileInput extends Inputs {

	use HasMultiple;

	/**
	 * @var UploadedFile
	 */
	protected $file;

	/**
	 * @return mixed
	 */
	public function getFile() {
		return $this->file;
	}

	/**
	 * @param mixed $file
	 */
	public function setFile( $file ): void {
		$this->file = $file;
	}

	public function types() : InputTypeCollection
	{
		return InputTypeCollection::fromTypes([
			Folder::input(),
			ReferenceType::input(),
			ReferenceId::input(),
			Type::input(),
			Ext::input(),
			Size::input(),
			IsPublic::input()
		]);
	}

	/**
	 * @param UploadedFile $file The uploaded file
	 * @param array $inputs The inputs from the request
	 * @param boolean $is_public If the file is public
	 *
	 * @return FileInput
	 */
	static function fromUploadedFile(UploadedFile $file, array $inputs = [], $is_public = false){
		$input = new static([
			'folder' => Arr::get($inputs, 'folder'),
			'reference_type' => Arr::get($inputs, 'reference_type'),
			'reference_id' => Arr::get($inputs, 'reference_id'),
			'type' => $file->getMimeType(),
			'ext'  => $file->getClientOriginalExtension(),
			'size' => round($file->getSize() / 1000, 2),
			'is_public' => $is_public
		]);

		$input->setFile($file);
		return $input;
	}

}