<?php

namespace Foundry\System\Entities\Contracts;

use Foundry\System\Entities\Folder;

interface HasFolder {

	/**
	 * @param Folder $folder
	 */
	public function setFolder( Folder $folder ): void;

	/**
	 * @return Folder|null
	 */
	public function getFolder();
}