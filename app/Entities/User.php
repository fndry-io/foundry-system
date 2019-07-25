<?php

namespace Foundry\System\Entities;

use Carbon\Carbon;
use Foundry\Core\Entities\Contracts\HasApiToken;
use Foundry\Core\Entities\Contracts\HasIdentity;
use Foundry\Core\Entities\Contracts\IsSoftDeletable;
use Foundry\Core\Entities\Entity;
use Foundry\Core\Entities\Traits\ApiTokenable;
use Foundry\Core\Entities\Traits\Identifiable;
use Foundry\Core\Entities\Traits\Uuidable;
use Foundry\Core\Entities\Traits\SoftDeleteable;
use Foundry\Core\Entities\Traits\Timestampable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Support\Facades\Hash;
use LaravelDoctrine\ORM\Auth\Authenticatable;
use LaravelDoctrine\ORM\Notifications\Notifiable;

use LaravelDoctrine\ACL\Contracts\HasPermissions as HasPermissionsContract;
use LaravelDoctrine\ACL\Contracts\HasRoles as HasRolesContract;


/**
 * Class User Entity
 *
 * @package Foundry\System\Entities
 *
 * @property Carbon $last_login_at
 * @property Boolean $logged_in
 *
 */
class User extends Entity implements \Illuminate\Contracts\Auth\Authenticatable, \Illuminate\Contracts\Auth\CanResetPassword, HasApiToken, HasIdentity, IsSoftDeletable {

	use Uuidable;
	use SoftDeleteable;
	use Timestampable;
	use Authenticatable;
	use CanResetPassword;
	use Notifiable;
	use ApiTokenable;
	use Identifiable;
//	use HasPermissions;
//	use HasRoles;

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
		'password',
		'super_admin',
		'active'
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
		'supervisor'
	];

	protected $email;

	protected $password;

	protected $username;

	protected $display_name;

	protected $active = false;

	protected $super_admin = false;

	protected $timezone;

	protected $last_login_at;

	protected $logged_in = false;

	protected $job_title;

	protected $job_department;

	protected $supervisor;

//	/**
//	 * @ACL\HasRoles()
//	 * @var \Doctrine\Common\Collections\ArrayCollection|\LaravelDoctrine\ACL\Contracts\Role[]
//	 */
//	protected $roles;

//	/**
//	 * @ACL\HasPermissions()
//	 * @var \Doctrine\Common\Collections\ArrayCollection|\LaravelDoctrine\ACL\Contracts\Permission[]
//	 */
//	public $permissions;
//
//	/**
//	 * @return \Doctrine\Common\Collections\ArrayCollection|\LaravelDoctrine\ACL\Contracts\Role[]
//	 */
//	public function getRoles()
//	{
//		return $this->roles;
//	}

//	/**
//	 * @return \Doctrine\Common\Collections\ArrayCollection|\LaravelDoctrine\ACL\Contracts\Permission[]
//	 */
//	public function getPermissions()
//	{
//		return $this->permissions;
//	}

	/**
	 * User constructor.
	 *
	 * @param array $properties
	 *
	 */
	public function __construct( array $properties = [] ) {
		parent::__construct( $properties );
		$this->setUuid();
		$this->setTimezone(config('app.timezone'));
	}

	/**
	 * @return string
	 */
	public function getAuthIdentifierName()
	{
		return 'id';
	}

	/**
	 * @return mixed
	 */
	public function getAuthIdentifier()
	{
		return $this->getId();
	}

	/**
	 * @param string $password
	 */
	public function setPassword( string $password ): void {
		$this->password = Hash::make($password);
	}

	/**
	 * @return string
	 */
	public function getUsername(): string {
		return $this->username;
	}

	/**
	 * @param string $username
	 */
	public function setUsername( string $username ): void {
		$this->username = $username;
	}

	/**
	 * @return string
	 */
	public function getDisplayName(): string {
		return $this->display_name;
	}

	/**
	 * @param string $display_name
	 */
	public function setDisplayName( string $display_name ): void {
		$this->display_name = $display_name;
	}

	/**
	 * @return mixed
	 */
	public function getEmail() {
		return $this->email;
	}

	/**
	 * @param mixed $email
	 */
	public function setEmail( $email ): void {
		$this->email = $email;
	}

	/**
	 * @return bool
	 */
	public function isActive(): bool {
		return $this->active;
	}

	/**
	 * @param bool $active
	 */
	public function setActive( bool $active ): void {
		$this->active = $active;
	}

	/**
	 * @return bool
	 */
	public function isAdmin(): bool {
		return false;
	}

	/**
	 * @return bool
	 */
	public function isSuperAdmin(): bool {
		return $this->super_admin;
	}

	/**
	 * @param bool $super_admin
	 */
	public function setSuperAdmin( bool $super_admin ): void {
		$this->super_admin = $super_admin;
	}

	/**
	 * @return string
	 */
	public function getTimezone(): string {
		return $this->timezone;
	}

	/**
	 * @param string $timezone
	 */
	public function setTimezone( string $timezone ): void {
		$this->timezone = $timezone;
	}


	public function can($ability)
	{
		//todo correct this to the permissions
		return true;
	}

	/**
	 * @return mixed
	 */
	public function getSupervisor() {
		return $this->supervisor;
	}

	/**
	 * @param mixed $supervisor
	 */
	public function setSupervisor( $supervisor ): void {
		$this->supervisor = $supervisor;
	}

	/**
	 * @return mixed
	 */
	public function getJobTitle() {
		return $this->job_title;
	}

	/**
	 * @param mixed $job_title
	 */
	public function setJobTitle( $job_title ): void {
		$this->job_title = $job_title;
	}

	/**
	 * @return mixed
	 */
	public function getJobDepartment() {
		return $this->job_department;
	}

	/**
	 * @param mixed $job_department
	 */
	public function setJobDepartment( $job_department ): void {
		$this->job_department = $job_department;
	}


}