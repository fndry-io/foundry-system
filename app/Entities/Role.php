<?php

namespace Foundry\System\Entities;

use Carbon\Carbon;
use Doctrine\ORM\Mapping as ORM;
use Foundry\Core\Entities\Entity;
//use LaravelDoctrine\ACL\Contracts\Role as RoleContract;

/**
 * Class Role Entity
 *
 * @package Foundry\System\Entities
 * @ORM\Entity(repositoryClass="Foundry\System\Repositories\RoleRepository")
 * @ORM\Table(name="roles")
 *
 */
class Role extends Entity {

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
}