<?php

namespace Foundry\System\Http\Requests\Files;

use Foundry\Core\Requests\Response;
use Foundry\System\Services\FileService;
use Illuminate\Support\Facades\Auth;

abstract class BaseDeleteFileRequest extends FileRequest {

	public function authorize() {
		//todo add permission check and is the user the owner of the file
        return ($this->user() && $this->user()->can('delete files'));
	}

	/**
	 * @return Response
	 * @throws \Exception
	 */
	public function handle(): Response
	{
		return FileService::service()->delete($this->getEntity());
	}

}
