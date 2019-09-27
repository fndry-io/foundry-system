<?php

namespace Foundry\System\Entities\Contracts;

interface HasFolder {

	/**
	 * @param IsFolder $folder
	 */
	public function setFolder( IsFolder $folder ): void;

	/**
	 * @return IsFolder|null
	 */
	public function getFolder();
}