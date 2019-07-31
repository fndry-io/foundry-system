<?php
namespace Foundry\System\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Foundry\Core\Entities\Contracts\HasIdentity;
use Foundry\Core\Entities\Contracts\IsNestedTreeable;
use Foundry\Core\Entities\Contracts\IsSoftDeletable;
use Foundry\Core\Entities\Entity;
use Foundry\Core\Entities\Traits\Identifiable;
use Foundry\Core\Entities\Traits\NestedTreeable;
use Foundry\Core\Entities\Traits\Referencable;
use Foundry\Core\Entities\Traits\SoftDeleteable;
use Foundry\Core\Entities\Traits\Timestampable;
use Foundry\Core\Entities\Traits\Uuidable;

/**
 * Class Folder
 *
 * A folder entity for folders which virtually store files
 *
 * @package Foundry\System\Entities
 */
class Folder extends Entity implements HasIdentity, IsSoftDeletable, IsNestedTreeable {

	use Identifiable;
	use SoftDeleteable;
	use Timestampable;
	use Referencable;
	use Uuidable;
	use NestedTreeable;

	protected $fillable = [
		'name'
	];

	protected $visible = [
		'id',
		'uuid',
		'name',
	];

	protected $name;

	/**
	 * @var File[]
	 */
	protected $files;

	public function __construct( array $properties = [] ) {
		parent::__construct( $properties );
		$this->files = new ArrayCollection();
	}

}