<?php

namespace Foundry\System\Model\Traits;

use Foundry\Core\Entities\Contracts\HasFolder;
use Foundry\Core\Entities\Contracts\IsFolder;
use Foundry\Core\Entities\Contracts\IsSoftDeletable;
use Foundry\System\Models\Folder;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait Folderable
{

	protected static function bootFolderable()
	{
		static::created(function (HasFolder $model) {
			/**@var Folderable $model */
			if ( ! $model->getFolder()) {
				$model->makeFolder();
			}
		});
		static::deleted(function (HasFolder $model) {
			/**@var Folder $folder*/
			if ($folder = $model->getFolder()) {
				if ($model instanceof IsSoftDeletable && $model->isForceDeleting()) {
					$folder->forceDelete();
				} else {
					$folder->delete();
				}
			}
		});
        static::restored(function (HasFolder $model) {
            /**@var Folder $folder*/
            if ($folder = $model->getFolder()) {
                $folder->restore();
            }
        });
	}

	/**
	 * @return MorphOne
	 */
	public function folder()
	{
		return $this->morphOne(Folder::class, 'reference')->withoutGlobalScopes();
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
	 * @return null|IsFolder
	 */
	public function getFolderParent(): ?IsFolder
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
	 * @return IsFolder|null
	 */
	public function getFolder(): ?IsFolder
	{
		return $this->folder;
	}

	/**
	 * Make the folder for the model
	 *
	 * @return IsFolder
     * @throws \ReflectionException
     */
	public function makeFolder(): IsFolder
	{
		if ( ! $this->getFolder()) {
			$folder = new Folder(['name' => $this->getFolderName()]);
			$folder->reference()->associate($this);
			//get the parent
			if ($parent = $this->getFolderParent()) {
				$folder->parent()->associate($parent->getKey());
			}
			$folder->save();
            $this->load('folder');
            return $folder;
		}

		return $this->getFolder();
	}
}
