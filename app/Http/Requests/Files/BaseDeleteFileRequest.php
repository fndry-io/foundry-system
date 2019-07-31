<?php

namespace Foundry\System\Http\Requests\Files;

use Foundry\Core\Requests\Contracts\EntityRequestInterface;
use Foundry\Core\Requests\FormRequest;
use Foundry\Core\Requests\Response;
use Foundry\Core\Requests\Traits\HasEntity;
use Foundry\System\Entities\File;
use Foundry\System\Services\FileService;
use Illuminate\Support\Facades\Auth;
use LaravelDoctrine\ORM\Facades\EntityManager;

abstract class BaseDeleteFileRequest extends FormRequest implements EntityRequestInterface {

	use HasEntity;

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

	public function findEntity( $id ) {
		return EntityManager::getRepository(File::class)->find($id);
	}
}
