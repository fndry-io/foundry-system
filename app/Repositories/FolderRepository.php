<?php

namespace Foundry\System\Repositories;

use Foundry\Core\Models\Model;
use Foundry\Core\Repositories\ModelRepository;
use Foundry\System\Models\Contracts\HasFolder;
use Foundry\System\Models\Folder;

class FolderRepository extends ModelRepository {

	/**
	 * @return string|Model
	 */
	public function getClassName()
	{
		return Folder::class;
	}

	/**
	 *
	 *
	 * @param HasFolder $entity
	 * @param string|null $name
	 * @param Folder|null $parent
	 * @param bool|null $create
	 *
	 * @return bool|Folder|null|object
	 * @throws \ReflectionException
	 */
	public function getRootFolderByEntity(HasFolder $entity, string $name = null, Folder $parent = null, bool $create = null)
	{
		$class = get_class($entity);

		$folder = $this->findOneBy(['reference_type' => $class, 'reference_id' => $entity->getKey()]);
		if (!$folder && $create) {
			$folder = new Folder();
			if (empty($name)){
				$name = $entity->getFolderName();
			}
			$folder->name = $name;
			$folder->reference()->associate($entity);
			if ($parent) {
				$folder->parent = $parent;
			}

			$folder->save();

			if ($entity instanceof HasFolder) {
				$entity->setFolder($folder);
			}
		}
		return $folder;
	}


}