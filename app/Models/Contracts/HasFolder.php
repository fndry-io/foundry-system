<?php

namespace Foundry\System\Models\Contracts;

use Foundry\System\Models\Folder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

interface HasFolder
{

	public function folder(): BelongsTo;

	/**
	 * @return string
	 * @throws \ReflectionException
	 */
	public function getFolderName(): string;

	/**
	 * @return null|Folder
	 */
	public function getFolderParent(): ?Folder;

	/**
	 * @return HasFolder
	 */
	public function getFolderableEntity(): ?HasFolder;

	/**
	 * @param Folder $folder
	 */
	public function setFolder(Folder $folder): void;

	/**
	 * @return Folder|null
	 */
	public function getFolder(): ?Folder;

	/**
	 * @return Folder
	 */
	public function makeFolder(): Folder;


}