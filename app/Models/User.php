<?php

namespace Foundry\System\Models;

use Carbon\Carbon;
use Foundry\Core\Entities\Contracts\IsSoftDeletable;
use Foundry\Core\Entities\Contracts\IsUser;
use Foundry\Core\Models\Traits\SoftDeleteable;
use Foundry\Core\Models\Traits\Uuidable;
use Foundry\Core\Models\Traits\Visible;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Traits\HasRoles as Roleable;

/**
 * Class User
 *
 * @property integer id
 * @property string uuid
 * @property string email
 * @property string password
 * @property boolean active
 * @property boolean super_admin
 * @property string timezone
 * @property Carbon last_login_at
 * @property boolean logged_in
 * @property string api_token
 * @property Carbon api_token_expires_at
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property Carbon deleted_at
 * @property string username
 * @property string display_name
 * @property User supervisor
 * @property string job_title
 * @property string job_department
 * @property array settings
 * @package Foundry\System\Models
 */
class User extends \Illuminate\Foundation\Auth\User implements IsUser, IsSoftDeletable
{
	use SoftDeleteable;
	use Uuidable;
	use Notifiable;
	use Visible;
    use Roleable;

	protected $table = 'users';

	/**
	 * @var array The fillable values
	 */
	protected $fillable = [
		'username',
		'display_name',
		'email',
		'job_title',
		'job_department'
	];

	protected $hidden = [
		'password'
	];

	protected $visible = [
		'id',
		'uuid',
		'username',
		'display_name',
		'email',
		'active',
		'super_admin',
		'timezone',
		'last_login_at',
		'created_at',
		'updated_at',
		'deleted_at',
		'username',
		'job_title',
		'job_department',
		'supervisor',
        'profile_url'
	];

	/**
	 * The attributes that should be cast to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'last_login_at' => 'datetime:Y-m-d\TH:i:sO',
		'api_token_expires_at' => 'datetime:Y-m-d\TH:i:sO',
		'created_at' => 'datetime:Y-m-d\TH:i:sO',
		'updated_at' => 'datetime:Y-m-d\TH:i:sO',
		'deleted_at' => 'datetime:Y-m-d\TH:i:sO',
		'settings' => 'array',
		'active' => 'boolean',
		'super_admin' => 'boolean',
		'logged_in' => 'boolean',
	];

	/**
	 * Get the token
	 *
	 * @return string|null
	 */
	public function getApiToken()
	{
		return $this->attributes['api_token'];
	}

	/**
	 * Set the Token
	 *
	 * @param string|null $token
	 */
	public function setApiToken($token)
	{
		$this->attributes['api_token'] = $token;
	}

	/**
	 * @return \DateTime
	 */
	public function getApiTokenExpiresAt(): \DateTime
	{
		return $this->attributes['api_token_expires_at'];
	}

	/**
	 * @param \DateTime $token
	 */
	public function setApiTokenExpiresAt(\DateTime $token = null)
	{
		$this->attributes['api_token_expires_at'] = $token;
	}

	public function supervisor()
	{
		return $this->belongsTo(User::class);
	}

	/**
	 * @param int|User $supervisor
	 */
	public function setSupervisorAttribute($supervisor)
	{
		if ($supervisor instanceof User) {
			$this->attributes['supervisor_id'] = $supervisor->getKey();
		} else {
			$this->attributes['supervisor_id'] = $supervisor;
		}
	}

	/**
	 * @return bool
	 */
	public function isActive() {
		return $this->active;
	}

	/**
	 * @return bool
	 */
	public function isAdmin() {
        return $this->isSuperAdmin() || $this->hasRole(config('permission.admin.role'));
	}

	/**
	 * @return bool
	 */
	public function isSuperAdmin() {
		return $this->super_admin;
	}

	public function setPasswordAttribute(string $password)
	{
		$this->attributes['password'] = Hash::make($password);
	}

    /**
     * @return BelongsTo
     */
    public function profile_image()
    {
        return $this->belongsTo(File::class)->withoutGlobalScopes();
    }

    public function getProfileUrlAttribute()
    {
        if ($this->relationLoaded('profile_image') && $this->profile_image) {
            return Storage::url($this->profile_image->name);
        } else {
            return url('/app/img/avatars/6.png');
        }
    }

}
