<?php

namespace Foundry\System\Entities;

use Foundry\Core\Entities\Entity;
use Foundry\Core\Entities\Traits\Timestampable;
use LaravelDoctrine\ACL\Contracts\Role as RoleContract;
use LaravelDoctrine\ACL\Permissions\HasPermissions;

/**
 * Class Role Entity
 *
 * @package Foundry\System\Entities
 *
 */
class Role extends Entity {

	use HasPermissions;
	use Timestampable;

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

	protected $id;

	protected $name;

	/**
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

//	/**
//	 * @ACL\HasPermissions
//	 */
//	public $permissions;
//
//	/**
//	 * @return \Doctrine\Common\Collections\ArrayCollection|\LaravelDoctrine\ACL\Contracts\Permission[]
//	 */
//	public function getPermissions()
//	{
//		return $this->permissions;
//	}

}