<?php

namespace Foundry\System\Entities\Traits;

use Foundry\System\Entities\Folder;
use LaravelDoctrine\ORM\Facades\EntityManager;

trait Folderable {

	/**
	 * @var Folder
	 */
	protected $folder;

	/**
	 * @var bool
	 */
	protected $auto_create_folder = true;

	/**
	 * @return string
	 * @throws \ReflectionException
	 */
	public function getFolderName() : string {
		return (new \ReflectionClass($this))->getShortName() . ' - ' . $this->getKey();
	}

	/**
	 * @return null|Folder
	 */
	public function getFolderParent() {
		return null;
	}

	/**
	 * @return $this
	 */
	public function getFolderableEntity() {
		return $this;
	}

	/**
	 * @param Folder $folder
	 */
	public function setFolder( Folder $folder ): void {
		$this->folder = $folder;
	}

	/**
	 * @return Folder|null
	 * @throws \ReflectionException
	 */
	public function getFolder() {
		if (!$this->folder && $folderable = $this->getFolderableEntity()) {
			$this->folder = EntityManager::getRepository(Folder::class)->getRootFolderByEntity($folderable, $this->getFolderName(), $this->getFolderParent(), $this->auto_create_folder);
		}
		return $this->folder;
	}
}