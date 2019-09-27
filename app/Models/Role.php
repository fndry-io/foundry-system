<?php

namespace Foundry\System\Models;

use Foundry\Core\Models\Model;

class Role extends Model
{
	protected $table = 'roles';

	/**
	 * @var array The fillable values
	 */
	protected $fillable = [
		'name'
	];

	protected $visible = [
		'id',
		'name'
	];

}