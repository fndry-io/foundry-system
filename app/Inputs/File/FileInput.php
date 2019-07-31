<?php

namespace Foundry\System\Inputs\File;

use Foundry\Core\Inputs\Inputs;
use Foundry\Core\Inputs\Types\Traits\HasMultiple;
use Foundry\Core\Support\InputTypeCollection;
use Foundry\System\Inputs\File\Types\Ext;
use Foundry\System\Inputs\File\Types\Size;
use Foundry\System\Inputs\File\Types\Type;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rules\In;

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
			Type::input(),
			Ext::input(),
			Size::input()
		]);
	}

	static function fromUploadedFile(UploadedFile $file, array $types = null, int $maxSize = null){
		$input = new FileInput([
			'type' => $file->getMimeType(),
			'ext'  => $file->getExtension(),
			'size' => round($file->getSize() / 1000, 2)
		]);
		if ($types) {
			$input->getType('type')->addRule(new In($types));
		}
		if ($maxSize) {
			$input->getType('size')->addRule('max:' . $maxSize);
		}
		$input->setFile($file);
		return $input;
	}

}