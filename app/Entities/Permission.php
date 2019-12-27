<?php

namespace Foundry\System\Entities;

use Doctrine\ORM\Mapping as ORM;
use LaravelDoctrine\ACL\Contracts\Permission as PermissionContract;
/**
 */
class Permission implements PermissionContract
{
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
	 * @ORM\Column(type="string")
	 */
	protected $group;

	/**
	 * @return mixed
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
	 * @param mixed $name
	 */
	public function setName($name)
	{
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getGroup()
	{
		return $this->name;
	}

	/**
	 * @param mixed $group
	 */
	public function setGroup($group)
	{
		$this->group = $group;
	}
}
