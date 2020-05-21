<?php

namespace Foundry\System\Http\Requests\Files;

use Foundry\Core\Requests\Contracts\EntityRequestInterface;
use Foundry\Core\Requests\FoundryFormRequest;
use Foundry\Core\Requests\Traits\HasEntity;
use Foundry\System\Repositories\FileRepository;

abstract class FileRequest extends FoundryFormRequest implements EntityRequestInterface {

	use HasEntity;

	public function findEntity( $id ) {
		return FileRepository::repository()->find($id);
	}
}
