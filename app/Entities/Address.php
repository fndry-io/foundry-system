<?php

namespace Foundry\System\Entities;

use Foundry\Core\Entities\Contracts\HasIdentity;
use Foundry\Core\Entities\Entity;
use Foundry\Core\Entities\Traits\Addressable;
use Foundry\Core\Entities\Traits\Identifiable;
use Foundry\Core\Entities\Traits\SoftDeleteable;
use Foundry\Core\Entities\Traits\Timestampable;

/**
 * Class Role Entity
 *
 * @package Foundry\System\Entities
 *
 */
class Address extends Entity implements HasIdentity {

	use Addressable;
	use Timestampable;
	use SoftDeleteable;
	use Identifiable;

	/**
	 * @var array The fillable values
	 */
	protected $fillable = [
		'type',
		'street',
		'city',
		'region',
		'country',
		'code',
	];

	protected $visible = [
		'id',
		'type',
		'street',
		'city',
		'region',
		'country',
		'code',
	];


}