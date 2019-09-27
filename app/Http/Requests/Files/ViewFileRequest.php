<?php

namespace Foundry\System\Http\Requests\Files;

use Foundry\Core\Requests\BaseFormRequest;
use Foundry\Core\Requests\Contracts\EntityRequestInterface;
use Foundry\Core\Requests\Traits\HasEntity;
use Foundry\System\Models\File;
use Foundry\System\Repositories\FileRepository;

class ViewFileRequest extends BaseFormRequest implements EntityRequestInterface {

	use HasEntity;

	/**
	 * @return bool
	 */
	public function authorize() {

		//TODO get this working
		return true;
		return $this->getEntity()->isPublic() || !!($this->user());
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
