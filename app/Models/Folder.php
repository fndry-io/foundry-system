<?php

namespace Foundry\System\Models;

use Foundry\Core\Entities\Contracts\HasReference;
use Foundry\Core\Entities\Contracts\IsFile;
use Foundry\Core\Entities\Contracts\IsFolder;
use Foundry\Core\Models\Model;
use Foundry\Core\Models\Traits\Referencable;
use Foundry\Core\Models\Traits\SoftDeleteable;
use Foundry\Core\Models\Traits\Uuidable;
use Kalnoy\Nestedset\NodeTrait;

/**
 * Class Folder
 * @package Foundry\System\Models
 */
class Folder extends Model implements IsFolder, HasReference
{
	use SoftDeleteable;
	use Uuidable;
	use Referencable;
	use NodeTrait;

	protected $table = 'system_folders';

	protected $deleteableFiles;

	protected $attributes = [
	    'is_file' => 0
    ];

	protected $fillable = [
		'name'
	];

	protected $visible = [
		'id',
		'uuid',
		'name',
        'alt'
	];

	public static function boot()
	{
		parent::boot();
		Folder::deleting(function(Folder $folder){
		    if (!$folder->isFile()) {
                $folder->setDeleteableFiles($folder->descendants()->get('file_id')->pluck('file_id'));
            }
		});
		Folder::deleted(function(Folder $folder){
		    if (!$folder->isFile()) {
                $files = File::query()->whereIn('id', $folder->getDeleteableFiles())->get();
                if ($folder->isForceDeleting()) {
                    foreach($files as $file) {
                        $file->forceDelete();
                    }
                } else {
                    foreach($files as $file) {
                        $file->delete();
                    }
                }
            }
			if ($folder->file) {
			    if ($folder->isForceDeleting()) {
                    $folder->file->forceDelete();
                } else {
                    $folder->file->delete();
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
		return $this->belongsTo(File::class)->withoutGlobalScopes();
	}

    public function getAltAttribute()
    {
        return $this->file ? ($this->file->alt ? $this->file->alt  : null ) : null;
    }

	public function setFile(IsFile $file)
	{
		$this->is_file = true;
		$this->file()->associate($file);
		if ($file->reference) {
		    $this->reference()->associate($file->reference);
        }
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
