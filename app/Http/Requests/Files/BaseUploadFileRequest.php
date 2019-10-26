<?php

namespace Foundry\System\Http\Requests\Files;

use Foundry\Core\Requests\Contracts\InputInterface;
use Foundry\Core\Requests\FormRequest;
use Foundry\Core\Requests\Response;
use Foundry\Core\Requests\Traits\HasInput;
use Foundry\System\Inputs\File\FileInput;
use Foundry\System\Services\FileService;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

abstract class BaseUploadFileRequest extends FormRequest implements InputInterface {

	use HasInput;

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
	public function fileSize()
	{
		return 15000;
	}

	/**
	 * The list of file types to support
	 */
	public function fileTypes()
	{
		return [];
	}

	public function isPublic()
	{
		return false;
	}

	/**
	 * The file input to use to validate the uploaded file
	 *
	 * Override this function to customise the file upload with your own max size and file types
	 *
	 * @param $inputs
	 *
	 * @see FileInput::fromUploadedFile
	 * @return \Foundry\Core\Inputs\Inputs|FileInput
	 */
	public function makeInput( $inputs ) {
		if (empty($this->file)) {
			throw new BadRequestHttpException('No file supplied');
		}
		$input = FileInput::fromUploadedFile($this->file, $inputs, $this->isPublic());
		return $input;
	}

	/**
	 * {@inheritdoc}
	 */
	public function handle(): Response
	{
		$validation = $this->input->validate();
		if (!$validation->isSuccess()) {
			return $validation;
		}

		return FileService::service()->add($this->input);
	}
}
