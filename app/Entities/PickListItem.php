<?php

namespace Foundry\System\Entities;

use Foundry\Core\Entities\Entity;
use Foundry\Core\Entities\Traits\Fillable;
use Foundry\Core\Entities\Traits\Timestampable;
use Foundry\Core\Entities\Traits\Visible;
use Foundry\Core\Entities\Contracts\HasIdentity;
use Foundry\Core\Entities\Traits\Identifiable;

/**
 * Class PickListItem
 *
 * @package Foundry\System\Entities
 */
class PickListItem extends Entity implements  HasIdentity
{
	use Identifiable;
	use Timestampable;
	use Fillable;
	use Visible;

	protected $fillable = [
		'label',
		'description',
		'sequence',
		'status'
	];

	protected $visible = [
		'id',
        'label',
        'description',
        'identifier',
        'sequence',
        'status',
		'created_at',
		'updated_at'
	];

	/**
	 * @var string
	 */
	protected $label;

	/**
     * @var string
     */
    protected $description;

	/**
	 * @var string
	 */
	protected $identifier;

    /**
     * @var integer
     */
    protected $sequence = 0;

	/**
	 * @var boolean
	 */
    protected $default_item = false;

    /**
     * @var PickList
     */
    protected $picklist;

    /**
     * @var Boolean
     */
    protected $status;

	/**
	 * @var boolean If the pick list is system generated
	 */
	protected $is_system = false;

}
