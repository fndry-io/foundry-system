<?php

namespace Foundry\System\Http\Requests\Files;

use Foundry\Core\Requests\Response;
use Foundry\System\Repositories\FileRepository;
use Foundry\System\Services\FileService;
use Illuminate\Support\Facades\Auth;

abstract class BaseDeleteFileRequest extends FileRequest {

	public function authorize() {
        return ($this->user() && $this->user()->can('delete files'));
	}

    public function findEntity( $id ) {
        return FileRepository::repository()->query()->withTrashed()->find($id);
    }

	/**
	 * @return Response
	 * @throws \Exception
	 */
	public function handle(): Response
	{
		return FileService::service()->delete($this->getEntity(), (boolean) $this->input('force', false));
	}

}
