<?php

namespace Foundry\System\Models;

use Foundry\Core\Entities\Contracts\IsFile;
use Foundry\Core\Entities\Contracts\IsFolder;
use Foundry\Core\Models\NodeReferenceModel;
use Foundry\Core\Models\Traits\Referencable;
use Foundry\Core\Models\Traits\SoftDeleteable;
use Foundry\Core\Models\Traits\Uuidable;
use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

/**
 * Class Folder
 * @package Foundry\System\Models
 */
class Folder extends NodeReferenceModel implements IsFolder
{
	use SoftDeleteable;
	use Uuidable;
	use Referencable;
	use NodeTrait;

	protected $table = 'folders';

	protected $deleteableFiles;

	protected $fillable = [
		'name'
	];

	protected $visible = [
		'id',
		'uuid',
		'name',
		'parent'
	];

	public static function boot()
	{
		parent::boot();
		Folder::deleting(function(Folder $folder){
			if ($folder->forceDeleting) {
				$folder->setDeleteableFiles($folder->descendants()->get('file_id')->pluck('file_id'));
			}
		});
		Folder::deleted(function(Folder $folder){
			if ($folder->forceDeleting) {
				$files = File::query()->whereIn('id', $folder->getDeleteableFiles())->get();
				foreach($files as $file) {
					$file->forceDelete();
				}
			}
		});
	}

	/**
	 * Determine if the folder is a directory
	 *
	 * @return bool
	 */
	public function isDirectory()
	{
		return !($this->file_id);
	}

	/**
	 * @return bool
	 */
	public function isFile()
	{
		return !!($this->file_id);
	}

	public function file()
	{
		return $this->belongsTo(File::class);
	}

	public function setFile(IsFile $file)
	{
		$this->is_file = true;
		$this->file()->associate($file);
	}

	public function setDeleteableFiles($files)
	{
		$this->deleteableFiles = $files;
	}

	/**
	 * @return mixed
	 */
	public function getDeleteableFiles()
	{
		return $this->deleteableFiles;
	}

	public function getLftName()
	{
		return 'lft';
	}

	public function getRgtName()
	{
		return 'rgt';
	}

	public function getParentIdName()
	{
		return 'parent_id';
	}

	/**
	 * @param Folder|int $folder
	 *
	 * @throws \Exception
	 */
	public function setParentAttribute($folder)
	{
		if ($folder instanceof Model) {
			$folder = $folder->getKey();
		}
		$this->setParentIdAttribute($folder);
	}

	public function getParent(): ?Folder
	{
		return $this->parent;
	}




}