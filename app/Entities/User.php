<?php

namespace Foundry\System\Entities;

use Carbon\Carbon;
use Doctrine\ORM\Mapping as ORM;
use Foundry\Core\Entities\Contracts\ApiTokenInterface;
use Foundry\Core\Entities\Entity;
use Foundry\Core\Entities\Traits\Uuidable;
use Gedmo\Mapping\Annotation as Gedmo;
use Foundry\Core\Entities\Traits\SoftDeletable;
use Foundry\Core\Entities\Traits\Timestampable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Support\Facades\Hash;
use Foundry\System\Repositories\UserRepository;
use LaravelDoctrine\ORM\Auth\Authenticatable;
use LaravelDoctrine\ORM\Notifications\Notifiable;

/**
 * Class User Entity
 *
 * @package Foundry\System\Entities
 * @ORM\Entity(repositoryClass="Foundry\System\Repositories\UserRepository")
 * @ORM\Table(name="users")
 *
 * @property Carbon $last_login_at
 * @property Boolean $logged_in
 */
class User extends Entity implements \Illuminate\Contracts\Auth\Authenticatable, \Illuminate\Contracts\Auth\CanResetPassword, ApiTokenInterface {

	use Uuidable;
	use SoftDeletable;
	use Timestampable;
	use Authenticatable;
	use CanResetPassword;
	use Notifiable;

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
	];

	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(type="integer")
	 */
	protected $id;

	/**
	 * @ORM\Column(type="string", nullable=false, unique=true)
	 */
	protected $email;

	/**
	 * @var string Password
	 * @ORM\Column(type="string", nullable=false)
	 */
	protected $password;

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
	 * @ORM\Column(type="boolean")
	 */
	protected $active = false;

	/**
	 * @var boolean Is Super Admin
	 * @ORM\Column(type="boolean")
	 */
	protected $super_admin = false;

	/**
	 * @var string Timezone
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $timezone;

	/**
	 * @ORM\Column(name="last_login_at", type="datetime", nullable=true)
	 * @var Carbon
	 */
	protected $last_login_at;

	/**
	 * @var boolean Logged in
	 * @ORM\Column(type="boolean")
	 */
	protected $logged_in = false;

	/**
	 * @ORM\Column(name="api_token", type="string", nullable=true)
	 */
	protected $api_token;

	/**
	 * @ORM\Column(name="api_token_expires_at", type="datetime", nullable=true)
	 */
	protected $api_token_expires_at;

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

}