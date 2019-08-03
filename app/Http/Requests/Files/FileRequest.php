<?php

namespace Foundry\System\Http\Requests\Files;

use Foundry\Core\Requests\Contracts\EntityRequestInterface;
use Foundry\Core\Requests\FormRequest;
use Foundry\Core\Requests\Traits\HasEntity;
use Foundry\System\Entities\File;
use LaravelDoctrine\ORM\Facades\EntityManager;

abstract class FileRequest extends FormRequest implements EntityRequestInterface {

	use HasEntity;

	public function findEntity( $id ) {
		return EntityManager::getRepository(File::class)->find($id);
	}
}
