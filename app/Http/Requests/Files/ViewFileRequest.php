<?php

namespace Foundry\System\Http\Requests\Files;

use Foundry\Core\Requests\BaseFormRequest;
use Foundry\Core\Requests\Contracts\EntityRequestInterface;
use Foundry\Core\Requests\Traits\HasEntity;
use Foundry\System\Entities\File;
use LaravelDoctrine\ORM\Facades\EntityManager;

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

		return EntityManager::getRepository(File::class)->find($id);
	}
}
