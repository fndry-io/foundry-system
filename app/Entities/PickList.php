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
class PickList extends Entity implements  HasIdentity
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
		'default_item'
	];

	protected $visible = [
		'id',
        'name',
        'description',
        'slug',
        'sequence',
        'status',
        'default_item',
		'items',
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
     * @var \Doctrine\Common\Collections\Collection|Item[]
     */
    protected $items;

    /**
     * @var \Doctrine\Common\Collections\Collection|Item[]
     */
    protected $status;


    protected $default_item;



}
