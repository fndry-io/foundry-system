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
			$folder = Folder::query()->withTrashed()->where('file_id', $file->getKey())->first();
			if ($file->forceDeleting) {
				Storage::delete($file->name);
				if ($folder){
					$folder->forceDelete();
				}
			} else {
				if ($folder && !$folder->isDeleted()){
					$folder->delete();
				}
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
		return $this->belongsTo(Folder::class)->withoutGlobalScopes();
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

    /**
     * Get the URL to the file
     *
     * @return string
     */
	public function getUrlAttribute()
    {

        if (config('filesystems.default') === 's3') {
            if (!$this->isPublic()) {
                /**
                 * Create a temp url with an expiry period
                 */
                return Storage::temporaryUrl( $this->name, now()->addMinutes(20) );
            } else {
                return Storage::url($this->name);
            }
        }

        if ($this->isPublic()) {
            return Storage::url($this->name);
        } else {
            return route('files.read', ['_entity' => $this->id], true);
        }

    }

}
