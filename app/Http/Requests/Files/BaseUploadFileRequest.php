<?php

namespace Foundry\System\Http\Requests\Files;

use Foundry\Core\Requests\FormRequest;
use Foundry\Core\Requests\Response;
use Foundry\System\Inputs\File\FileInput;
use Foundry\System\Services\FileService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;

abstract class BaseUploadFileRequest extends FormRequest {

	/**
	 * {@inheritdoc}
	 */
	public function authorize() {
		return !!(Auth::user());
	}

	/**
	 * @return array
	 */
	public function rules(){
		return [
			'file' => 'file'
		];
	}

	/**
	 * The file input to use to validate the uploaded file
	 *
	 * Override this function to customise the file upload with your own max size and file types
	 *
	 * @param UploadedFile $file
	 *
	 * @return FileInput
	 */
	protected function makeFileInput(UploadedFile $file) : FileInput
	{
		return FileInput::fromUploadedFile($file);
	}

	/**
	 * {@inheritdoc}
	 */
	public function handle(): Response
	{
		$input = $this->makeFileInput($this->file);
		$validate = $input->validate();
		if (!$validate->isSuccess()) {
			return $validate;
		}
		return FileService::service()->add($input);
	}
}
