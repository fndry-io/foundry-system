<?php

namespace Foundry\System\Entities;

use Carbon\Carbon;
use Foundry\Core\Entities\Entity;
use Foundry\Core\Entities\Traits\Addressable;
use Foundry\Core\Entities\Traits\SoftDeletable;
use Foundry\Core\Entities\Traits\Timestampable;

/**
 * Class Role Entity
 *
 * @package Foundry\System\Entities
 *
 */
class Address extends Entity {

	use Addressable;
	use Timestampable;
	use SoftDeletable;

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

	protected $id;

	public function __construct( array $properties = [] ) {
		parent::__construct( $properties );
		$this->setCreatedAt(new Carbon());
	}

	/**
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}

}