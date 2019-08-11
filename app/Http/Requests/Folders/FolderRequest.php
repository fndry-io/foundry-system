<?php

namespace Foundry\System\Http\Requests\Folders;

use Foundry\Core\Requests\Contracts\EntityRequestInterface;
use Foundry\Core\Requests\FormRequest;
use Foundry\Core\Requests\Traits\HasEntity;
use Foundry\System\Entities\Folder;
use LaravelDoctrine\ORM\Facades\EntityManager;

abstract class FolderRequest extends FormRequest implements EntityRequestInterface
{
	use HasEntity;

	/**
	 * @param mixed $id
	 *
	 * @return null|Folder|object
	 */
	public function findEntity($id)
	{
		return EntityManager::getRepository(Folder::class)->findOneBy(['id' => $id, 'is_file' => false]);
	}

}
