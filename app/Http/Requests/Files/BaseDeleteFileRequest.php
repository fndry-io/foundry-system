<?php

namespace Foundry\System\Http\Requests\Files;

use Foundry\Core\Requests\Response;
use Foundry\System\Services\FileService;
use Illuminate\Support\Facades\Auth;

abstract class BaseDeleteFileRequest extends FileRequest {

	/**
	 * {@inheritdoc}
	 */
	public function authorize() {
		//todo add permission check and is the user the owner of the file
		return !!(Auth::user());
	}

	/**
	 * {@inheritdoc}
	 */
	public function handle(): Response
	{
		return FileService::service()->delete($this->getEntity());
	}

}
