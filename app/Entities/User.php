<?php

namespace Foundry\System\Entities;

use Carbon\Carbon;
use Foundry\Core\Entities\Contracts\ApiTokenInterface;
use Foundry\Core\Entities\Entity;
use Foundry\Core\Entities\Traits\Uuidable;
use Foundry\Core\Entities\Traits\SoftDeletable;
use Foundry\Core\Entities\Traits\Timestampable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Support\Facades\Hash;
use Foundry\System\Repositories\UserRepository;
use LaravelDoctrine\ACL\Roles\HasRoles;
use LaravelDoctrine\ACL\Permissions\HasPermissions;
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
class User extends Entity implements \Illuminate\Contracts\Auth\Authenticatable, \Illuminate\Contracts\Auth\CanResetPassword, ApiTokenInterface {

	use Uuidable;
	use SoftDeletable;
	use Timestampable;
	use Authenticatable;
	use CanResetPassword;
	use Notifiable;
//	use HasPermissions;
//	use HasRoles;

	/**
	 * @var array The fillable values
	 */
	protected $fillable = [
		'first_name',
		'last_name',
		'email'
	];

	protected $hidden = [
		'password',
		'super_admin',
		'active'
	];

	protected $visible = [
		'id',
		'uuid',
		'first_name',
		'last_name',
		'email',
		'active',
		'super_admin',
		'timezone',
		'last_login_at',
		'created_at',
		'updated_at',
		'deleted_at',
		'username'
	];

	protected $id;

	protected $email;

	protected $password;

	protected $first_name;

	protected $last_name;

	protected $active = false;

	protected $super_admin = false;

	protected $timezone;

	protected $last_login_at;

	protected $logged_in = false;

	protected $api_token;

	protected $api_token_expires_at;

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
		$this->setCreatedAt(new Carbon());
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
		return $this->id;
	}

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
	 * @return string
	 */
	public function getFirstName(): string {
		return $this->first_name;
	}

	/**
	 * @param string $first_name
	 */
	public function setFirstName( string $first_name ): void {
		$this->first_name = $first_name;
	}

	/**
	 * @return string
	 */
	public function getLastName(): string {
		return $this->last_name;
	}

	/**
	 * @param string $last_name
	 */
	public function setLastName( string $last_name ): void {
		$this->last_name = $last_name;
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

	/**
	 * @return string
	 */
	public function getFullName()
	{
		return $this->first_name . ' ' . $this->last_name;
	}

	/**
	 * @return mixed
	 */
	public function getApiToken(){
		return $this->api_token;
	}

	/**
	 * @param string|null $token
	 */
	public function setApiToken(string $token = null)
	{
		$this->api_token = $token;
	}

	/**
	 * @return \DateTime
	 */
	public function getApiTokenExpiresAt()
	{
		return $this->api_token_expires_at;
	}

	/**
	 * @param Carbon|null $date
	 */
	public function setApiTokenExpiresAt(Carbon $date = null)
	{
		$this->api_token_expires_at = $date;
	}

	public function can($ability)
	{
		//todo correct this to the permissions
		return true;
	}

	public function getUsername()
	{
		return $this->first_name . " " . $this->last_name;
	}

}