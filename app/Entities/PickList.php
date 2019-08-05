<?php

namespace Foundry\System\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Foundry\Core\Entities\Entity;
use Foundry\Core\Entities\Traits\Fillable;
use Foundry\Core\Entities\Traits\Timestampable;
use Foundry\Core\Entities\Traits\Visible;
use Foundry\Core\Entities\Contracts\HasIdentity;
use Foundry\Core\Entities\Traits\Identifiable;

/**
 * Class PickList
 *
 * @package Foundry\System\Entities
 */
class PickList extends Entity implements HasIdentity
{
	use Identifiable;
	use Timestampable;
	use Fillable;
	use Visible;

	protected $fillable = [
		'label',
		'description'
	];

	protected $visible = [
		'id',
        'label',
        'description',
        'identifier',
        'default_item',
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
     * @var \Doctrine\Common\Collections\Collection|PickListItem[]
     */
    protected $items;

	/**
	 * @var int The id of the default item to select in this pick list
	 */
    protected $default_item;

	/**
	 * @var boolean If the pick list is system generated
	 */
	protected $is_system = false;

    public function __construct( array $properties = [] ) {
	    parent::__construct( $properties );

	    $this->items = new ArrayCollection();
    }


}
