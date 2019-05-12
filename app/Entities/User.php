<?php

namespace Foundry\System\Entities;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Foundry\Core\Entity;
use Foundry\Core\Traits\SoftDeletable;
use Foundry\Core\Traits\Timestampable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Class User
 * @package Foundry\System\Entities
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User extends Entity {

	use SoftDeletable;
	use Timestampable;

	/**
	 * @var array The fillable values
	 */
	protected $fillable = [
		'first_name',
		'last_name',
		'username',
		'active'
	];

	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(type="integer")
	 */
	protected $id;

	/**
	 * @ORM\Column(name="uuid", type="guid", unique=true)
	 * @ORM\GeneratedValue(strategy="UUID")
	 */
	protected $uuid;

	/**
	 *
	 * @var string Password
	 * @ORM\Column(type="string", length=32, unique=true, nullable=false)
	 */
	protected $username;

	/**
	 * @var string Password
	 * @ORM\Column(type="string", nullable=false)
	 */
	protected $password;

	/**
	 * @ORM\Column(type="string", nullable=false, unique=true)
	 */
	protected $email;

	/**
	 * @var string First Name
	 * @ORM\Column(type="string", nullable=false)
	 */
	protected $first_name;

	/**
	 * @var string Last Name
	 * @ORM\Column(type="string", nullable=false)
	 */
	protected $last_name;

	/**
	 * @var boolean Is Active
	 * @ORM\Column(type="boolean", options={"default":false})
	 */
	protected $active = false;

	/**
	 * @var boolean Is Super Admin
	 * @ORM\Column(type="boolean", options={"default":false})
	 */
	protected $super_admin = false;

	/**
	 * @param string $password
	 */
	public function setPassword( string $password ): void {
		$this->password = Hash::make($password);
	}

	/**
	 * @return mixed
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @return mixed
	 */
	public function getUuid() {
		return $this->uuid;
	}

	/**
	 * @return string
	 */
	public function getFirstName(): string {
		return $this->first_name;
	}

	/**
	 * @return string
	 */
	public function getLastName(): string {
		return $this->last_name;
	}


	/**
	 * @return string
	 */
	public function getUsername(): string {
		return $this->username;
	}

	/**
	 * @return mixed
	 */
	public function getEmail() {
		return $this->email;
	}

	/**
	 * @return bool
	 */
	public function isActive(): bool {
		return $this->active;
	}

	/**
	 * @return bool
	 */
	public function isSuperAdmin(): bool {
		return $this->super_admin;
	}

}