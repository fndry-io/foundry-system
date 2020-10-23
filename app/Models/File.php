<?php

namespace Foundry\System\Models;

use Foundry\Core\Models\Model;
use Foundry\Core\Models\Traits\Referencable;
use Foundry\Core\Models\Traits\SoftDeleteable;
use Foundry\Core\Models\Traits\Uuidable;
use Foundry\Core\Entities\Contracts\IsFile;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * Class File
 *
 * @property Folder $folder The folder this file is representing by in the system_folders
 *
 * @package Foundry\System\Models
 */
class File extends Model implements IsFile, Auditable
{
    use Uuidable;
    use SoftDeleteable;
    use Referencable;
    use AuditableTrait;

    protected $table = 'system_files';

    protected $attributes = [
        'is_public' => false
    ];

    protected $appends = [
        'url'
    ];

    protected $visible = [
		'id',
		'uuid',
		'original_name',
		'type',
		'size',
		'created_at',
		'updated_at',
		'deleted_at',
		'is_public',
        'url',
        'share_url',
        'alt'
    ];

	protected $fillable = [
		'name',
		'original_name',
		'type',
		'ext',
        'alt',
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
        static::deleted(function($file){
			if ($file->forceDeleting) {
				Storage::delete($file->name);
			}
            Cache::forget('system_files:' . $file->id);
		});

		static::saved(function(File $file){
		    if ($file->folder) {
		        if ($file->folder->name !== $file->original_name) {
                    $file->folder->name = $file->original_name;
                    $file->folder->save();
                } else {
                    $file->folder->touch();
                }
            }
            Cache::forget('system_files:' . $file->id);
        });
	}

	/**
	 * @return bool
	 */
	public function isPublic()
	{
		return $this->is_public;
	}

    /**
     * The folder which represents this file
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
	public function folder()
	{
		return $this->hasOne(Folder::class)->withoutGlobalScopes();
	}

    /**
     * Get the URL to the file
     *
     * @return string
     */
	public function getUrlAttribute()
    {

        if (config('filesystems.default') === 's3' || config('filesystems.default') === 'do_spaces') {
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
            return Storage::url(str_replace('public/', '', $this->name));
        } else {
            return route('system.files.read', ['_entity' => $this->id], true);
        }

    }

    /**
     * Get the URL to the file
     *
     * @return string
     */
    public function getShareUrlAttribute()
    {

        if (config('filesystems.default') === 's3' || config('filesystems.default') === 'do_spaces') {
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
            return url('/files/' . $this->id . '/' . $this->original_name);
        } else {
            return route('system.files.read', ['_entity' => $this->id], true);
        }

    }

    /**
     * @return BelongsTo
     */
    public function user()
    {
        return $this->morphTo('user');
    }

    /**
     * Returns the values for an input
     *
     * @return mixed
     */
    public function onlyForInput()
    {
        return $this->only('id', 'url', 'original_name', 'type', 'size', 'token');
    }

    /**
     * @param int $id The file ID
     * @return array
     */
    public static function getFile($id)
    {
        return Cache::rememberForever('system_files:' . $id, function() use ($id){
            $file = File::query()->find($id);
            return [
                'id'=> $file->id,
                'uuid' => $file->uuid,
                'ext' => $file->ext,
                'alt' => $file->alt,
                'url' => $file->url,
                'share_url' => $file->share_url
            ];
        });

    }

}
