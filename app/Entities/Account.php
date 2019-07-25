<?php

namespace Foundry\System\Entities;

use Foundry\Core\Entities\Contracts\HasIdentity;
use Foundry\Core\Entities\Traits\Identifiable;
use Foundry\Core\Entities\Traits\SoftDeleteable;
use Foundry\Core\Entities\Traits\Timestampable;
use LaravelDoctrine\ACL\Contracts\Organisation;

/**
 * Account
 *
 * @package Foundry\System\Entities
 */
class Account implements Organisation, HasIdentity
{
	use Identifiable;
	use Timestampable;
	use SoftDeleteable;

	protected $name;

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}
}