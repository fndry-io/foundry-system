<?php

namespace Foundry\System\Entities;

use Foundry\Core\Entities\Contracts\HasIdentity;
use Foundry\Core\Entities\Entity;
use Foundry\Core\Entities\Traits\Identifiable;
use Foundry\Core\Entities\Traits\Timestampable;

/**
 * Class Role Entity
 *
 * @package Foundry\System\Entities
 *
 */
class Role extends Entity implements HasIdentity {

	use Timestampable;
	use Identifiable;

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

	protected $name;


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