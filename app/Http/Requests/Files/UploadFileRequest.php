<?php

namespace Foundry\System\Http\Requests\Files;

use Foundry\System\Inputs\File\FileInput;

class UploadFileRequest extends BaseUploadFileRequest {

	/**
	 * {@inheritdoc}
	 */
	public static function name(): String {
		return 'foundry.system.files.upload';
	}

	/**
	 *
	 * @param $inputs
	 *
	 * @see FileInput::fromUploadedFile
	 * @return \Foundry\Core\Inputs\Inputs|FileInput
	 */
	public function makeInput( $inputs ) {
		$input = FileInput::fromUploadedFile($this->file, $inputs, ['jpeg', 'jpg', 'png', 'gif', 'doc', 'docx', 'pdf', 'xls', 'xlsx', 'txt'], 10000);
		return $input;
	}
}
