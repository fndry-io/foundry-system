<?php

namespace Foundry\System\Models;

use Foundry\Core\Models\Traits\Sluggable;

class Role extends \Spatie\Permission\Models\Role {

    use Sluggable;

    protected $table = 'roles';

    protected $sluggable = 'name';

    /**
     * @var array The fillable values
     */
    protected $fillable = [
        'name',
        'guard_name'
    ];

    protected $visible = [
        'id',
        'name',
        'guard_name',
        'slug'
    ];

    protected $hidden = [
		'created_at',
		'updated_at',
		'model_type',
		'model_id',
		'pivot'
	];

	public function isAdminRole()
	{
		return ($this->name === config('permissions.admin.role'));
	}

}
