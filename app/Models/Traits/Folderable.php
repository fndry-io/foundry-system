<?php

namespace Foundry\System\Model\Traits;

use Foundry\Core\Entities\Contracts\HasNode;
use Foundry\System\Models\Contracts\HasFolder;
use Foundry\System\Models\Folder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait Folderable
{

	protected static function bootFolderable()
	{
		static::created(function (HasFolder $model) {
			/**@var $model HasFolder */
			if ( ! $model->getFolder()) {
				$model->makeFolder();
			}
		});
	}

	/**
	 * @return BelongsTo
	 */
	public function folder(): BelongsTo
	{
		$this->belongsTo(Folder::class);
	}

	/**
	 * @return string
	 * @throws \ReflectionException
	 */
	public function getFolderName(): string
	{
		return (new \ReflectionClass($this))->getShortName() . ' - ' . $this->getKey();
	}

	/**
	 * @return null|Folder
	 */
	public function getFolderParent(): ?Folder
	{
		return null;
	}

	/**
	 * @return HasFolder
	 */
	public function getFolderableEntity(): ?HasFolder
	{
		return $this;
	}

	/**
	 * @param Folder $folder
	 */
	public function setFolder(Folder $folder): void
	{
		$this->folder()->associate($folder);
	}

	/**
	 * @return Folder|null
	 */
	public function getFolder(): ?Folder
	{
		return $this->folder;
	}

	/**
	 * Make the folder for the model
	 *
	 * @return Folder
	 */
	public function makeFolder(): Folder
	{
		if ( ! $this->getFolder()) {
			$folder = new Folder();
			$folder->reference()->associate($this);
			//get the parent
			if ($parent = $this->getFolderParent()) {
				$folder->setParentId($parent->getKey());
			}
			//if the model has a node, linked it too
			if ($this instanceof HasNode) {
				$folder->node()->associate($this->getNode());
			}
			$folder->save();
			$this->folder()->associate($folder);
			$this->save();
		}

		return $this->getFolder();
	}
}