<?php

namespace Foundry\System\Http\Requests\Files;

use Foundry\Core\Requests\Contracts\InputInterface;
use Foundry\Core\Requests\FormRequest;
use Foundry\Core\Requests\Response;
use Foundry\Core\Requests\Traits\HasInput;
use Foundry\System\Inputs\File\FileInput;
use Foundry\System\Services\FileService;
use Illuminate\Support\Facades\Auth;

abstract class BaseUploadFileRequest extends FormRequest implements InputInterface {

	use HasInput;

	/**
	 * {@inheritdoc}
	 */
	public function authorize() {
		return !!(Auth::user());
	}

	/**
	 * @return array
	 */
	public function rules() {
		return [
			'file' => 'file'
		];
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
	abstract public function makeInput( $inputs );

	/**
	 * {@inheritdoc}
	 */
	public function handle(): Response
	{
		return FileService::service()->add($this->input);
	}
}
