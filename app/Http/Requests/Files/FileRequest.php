<?php

namespace Foundry\System\Http\Requests\Files;

use Foundry\Core\Requests\Contracts\EntityRequestInterface;
use Foundry\Core\Requests\FormRequest;
use Foundry\Core\Requests\Traits\HasEntity;
use Foundry\System\Repositories\FileRepository;

abstract class FileRequest extends FormRequest implements EntityRequestInterface {

	use HasEntity;

	public function findEntity( $id ) {
		return FileRepository::repository()->find($id);
	}
}
