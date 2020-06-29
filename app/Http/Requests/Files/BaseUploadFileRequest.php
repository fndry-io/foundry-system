<?php

namespace Foundry\System\Http\Requests\Files;

use Foundry\Core\Requests\FoundryFormRequest;
use Foundry\System\Inputs\File\FileInput;
use Foundry\System\Models\Folder;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

abstract class BaseUploadFileRequest extends FoundryFormRequest {

	/**
	 * {@inheritdoc}
	 */
	public function authorize() {
        return ($this->user() && $this->user()->can('upload files'));
	}

	/**
	 * @return array
	 */
	public function rules() {

		$rules = ['file'];
		if ($types = $this->fileTypes()) {
			$rules[] = 'mimes:' . implode(',', $types);
		}
		if ($size = $this->fileSize()) {
			$rules[] = 'max:' . $size;
		}
		return [
            'file' => $rules
		];
	}

	/**
	 * The max file size to allow
	 *
	 * @return int
	 */
	static function fileSize()
	{
		return 64000;
	}

	/**
	 * The list of file types to support
	 */
	static function fileTypes()
	{
		return [];
	}

	public function isPublic()
	{
		return false;
	}

    /**
     * The folder the file should be added to
     *
     * @return Folder|null
     */
	public function folder()
    {
        return null;
    }

	/**
	 * The file input to use to validate the uploaded file
	 *
	 * Override this function to customise the file upload with your own max size and file types
	 *
	 * @param array $inputs The inputs from the request
	 *
	 * @see FileInput::fromUploadedFile
	 * @return \Foundry\Core\Inputs\Inputs|FileInput
	 */
	public function makeInput( $inputs ) {
		if (empty($this->file)) {
			throw new BadRequestHttpException('No file supplied');
		}
		$input = FileInput::fromUploadedFile($this->file, $inputs, $this->isPublic());

		if (!$input->value('folder') && ($folder = $this->folder())) {
		    $input->setValue('folder', $folder->getKey());
        }

		return $input;
	}

}
