<?php

namespace Foundry\System\Entities;

use Doctrine\ORM\Mapping as ORM;
use LaravelDoctrine\ACL\Mappings as ACL;
use Foundry\Core\Entities\Entity;
use LaravelDoctrine\ACL\Contracts\Role as RoleContract;
use LaravelDoctrine\ACL\Permissions\HasPermissions;

/**
 * Class Role Entity
 *
 * @package Foundry\System\Entities
 * @ORM\Entity(repositoryClass="Foundry\System\Repositories\RoleRepository")
 * @ORM\Table(name="roles")
 *
 */
class Role extends Entity implements RoleContract {

	use HasPermissions;

	/**
	 * @var array The fillable values
	 */
	protected $fillable = [
		'name'
	];

	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\Column(type="string")
	 */
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

	/**
	 * @ACL\HasPermissions
	 */
	public $permissions;

	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection|\LaravelDoctrine\ACL\Contracts\Permission[]
	 */
	public function getPermissions()
	{
		return $this->permissions;
	}

}