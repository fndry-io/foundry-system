<?php

namespace Foundry\System\Http\Requests\Files;

use Foundry\Core\Requests\Response;
use Foundry\System\Repositories\FileRepository;
use Foundry\System\Services\FileService;

abstract class BaseDeleteFileRequest extends FileRequest {

	public function authorize() {
        return ($this->user() && ($this->user()->can('delete files') || ($this->getEntity()->user && $this->getEntity()->user->id === $this->user()->id)));
	}

    public function findEntity( $id ) {
        return FileRepository::repository()->query()->withTrashed()->where('id', $id)->where('token', $this->input('token'))->first();
    }

    public function rules()
    {
        return [
            'token' => 'required'
        ];
    }

	/**
	 * @return Response
	 * @throws \Exception
	 */
	public function handle(): Response
	{
		return FileService::service()->delete($this->getEntity(), $this->input('token'), (boolean) $this->input('force', false));
	}

}
