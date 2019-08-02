<?php

namespace Foundry\System\Entities;


use Foundry\Core\Entities\Entity;
use Foundry\Core\Entities\Traits\Fillable;
use Foundry\Core\Entities\Traits\Timestampable;
use Foundry\Core\Entities\Traits\Visible;
use Foundry\Core\Entities\Contracts\HasReference;
use Foundry\Core\Entities\Contracts\HasIdentity;
use Foundry\Core\Entities\Traits\Identifiable;
use Foundry\Core\Entities\Traits\Referencable;

/**
 * Checklist
 */
class PickListItem extends Entity implements  HasIdentity
{
	use Identifiable;
	use Timestampable;
	use Fillable;
	use Visible;


	protected $fillable = [
		'name',
		'description',
		'slug',
		'sequence',
		'status',
        'picklist',
		'default_item'
	];

	protected $visible = [
		'id',
        'name',
        'description',
        'slug',
        'sequence',
        'status',
        'picklist',
		'items',
        'default_item',
		'created_at',
		'updated_at'
	];

	/**
	 * @var string
	 */
	protected $name;

	/**
     * @var string
     */
    protected $description;

	/**
	 * @var string
	 */
	protected $slug;

    /**
     * @var number|null
     */
    protected $sequence = 0;


    protected $default_item=0;
    /**
     * @var Picklist
     */
    protected $picklist;

    /**
     * @var Boolean
     */
    protected $status = false;


}
