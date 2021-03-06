<?php

namespace Foundry\System\Http\Requests\Folders;

use Foundry\Core\Entities\Contracts\IsFolder;
use Foundry\Core\Requests\Contracts\EntityRequestInterface;
use Foundry\Core\Requests\FormRequest;
use Foundry\Core\Requests\Traits\HasEntity;
use Foundry\System\Models\Folder;
use Foundry\System\Repositories\FolderRepository;

/**
 * Class FolderRequest
 *
 * @method IsFolder getEntity()
 *
 * @package Foundry\System\Http\Requests\Folders
 */
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
		return FolderRepository::repository()->findOneBy(['id' => $id, 'is_file' => false]);
	}

}
