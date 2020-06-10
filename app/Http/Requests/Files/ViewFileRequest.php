<?php

namespace Foundry\System\Http\Requests\Files;

use Foundry\Core\Requests\Contracts\EntityRequestInterface;
use Foundry\Core\Requests\FoundryFormRequest;
use Foundry\Core\Requests\Traits\HasEntity;
use Foundry\System\Models\File;
use Foundry\System\Repositories\FileRepository;

class ViewFileRequest extends FoundryFormRequest implements EntityRequestInterface {

	use HasEntity;

    /**
	 * @return bool
	 */
	public function authorize()
    {
        $user = $this->user();
		return $this->getEntity()->isPublic()
            || ($user && $user->can('system.files.read'))
            || ($user && $this->getEntity()->user && $user->id && $this->getEntity()->user->id);
	}

	/**
	 * Find the Entity for the request
	 *
	 * @param $id
	 *
	 * @return File|null|object
	 */
	public function findEntity( $id ) {
		return FileRepository::repository()->find($id);
	}
}
