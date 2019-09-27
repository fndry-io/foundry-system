<?php

namespace Foundry\System\Models;

use Foundry\Core\Models\NodeReferenceModel;
use Foundry\Core\Models\Traits\Referencable;
use Foundry\Core\Models\Traits\Uuidable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kalnoy\Nestedset\NodeTrait;

class Folder extends NodeReferenceModel
{
	use SoftDeletes;
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
		File::deleted(function(Folder $folder){
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

	public function setFile(File $file)
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
	 * @param $value
	 *
	 * @throws \Exception
	 */
	public function setParentAttribute($value)
	{
		$this->setParentIdAttribute($value);
	}

	public function getParent(): ?Folder
	{
		return $this->parent;
	}


}