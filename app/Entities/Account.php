<?php

namespace Foundry\System\Entities;

use LaravelDoctrine\ACL\Contracts\Organisation;

/**
 * Account
 *
 * @package Foundry\System\Entities
 */
class Account implements Organisation
{
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
}