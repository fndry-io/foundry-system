<?php
namespace Foundry\System\Entities;

use Foundry\Core\Entities\Contracts\HasIdentity;
use Foundry\Core\Entities\Contracts\IsReferenceable;
use Foundry\Core\Entities\Contracts\IsSoftDeletable;
use Foundry\Core\Entities\Entity;
use Foundry\Core\Entities\Traits\Identifiable;
use Foundry\Core\Entities\Traits\Referencable;
use Foundry\Core\Entities\Traits\SoftDeleteable;
use Foundry\Core\Entities\Traits\Timestampable;
use Foundry\Core\Entities\Traits\Uuidable;

/**
 * Class File
 *
 * A file entity for storing a file in the system
 *
 * @package Foundry\System\Entities
 */
class File extends Entity implements HasIdentity, IsSoftDeletable, IsReferenceable {

	use Identifiable;
	use SoftDeleteable;
	use Timestampable;
	use Referencable;
	use Uuidable;

	protected $visible = [
		'id',
		'uuid',
		'name',
		'original_name',
		'type',
		'ext',
		'size',
		'created_at',
		'updated_at',
		'deleted_at'
	];

	protected $fillable = [
		'name',
		'original_name',
		'type',
		'ext',
		'size'
	];

	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @var string
	 */
	protected $original_name;

	/**
	 * @var string
	 */
	protected $type;

	/**
	 * @var string
	 */
	protected $ext;

	/**
	 * @var integer
	 */
	protected $size;

	/**
	 * @var Folder
	 */
	protected $folder;


	public function __construct( array $properties = [] ) {
		parent::__construct( $properties );
		$this->setUuid();
	}

}