<?php

namespace Foundry\System\Models;

use Foundry\Core\Models\Model;
use Foundry\Core\Models\Traits\Referencable;
use Foundry\Core\Models\Traits\SoftDeleteable;
use Foundry\Core\Models\Traits\Uuidable;
use Foundry\Core\Entities\Contracts\IsFile;
use Illuminate\Support\Facades\Storage;

class File extends Model implements IsFile
{
	use Uuidable;
	use SoftDeleteable;
	use Referencable;

	protected $table = 'files';

	protected $visible = [
		'id',
		'uuid',
		'original_name',
		'type',
		'size',
		'created_at',
		'updated_at',
		'deleted_at',
		'is_public'
	];

	protected $fillable = [
		'name',
		'original_name',
		'type',
		'ext',
		'size',
		'is_public',
		'reference_type',
		'reference_id'
	];

	protected $casts = [
		'is_public' => 'boolean'
	];

	public static function boot()
	{
		parent::boot();
		File::deleted(function($file){
			if ($file->forceDeleting) {
				Storage::delete($file->name);
			}
		});
	}

	/**
	 * @return bool
	 */
	public function isPublic()
	{
		return $this->is_public;
	}

	public function folder()
	{
		return $this->belongsTo(Folder::class);
	}

	/**
	 * @param int|Folder $folder
	 */
	public function setFolderAttribute($folder)
	{
		if(!$folder instanceof Folder && !empty($folder)) {
			$folder = Folder::query()->find($folder);
		}
		if ($folder) {
			$this->folder()->associate($folder);
		}
	}

}