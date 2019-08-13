<?php
namespace Foundry\System\Entities;

use Foundry\Core\Entities\Contracts\HasIdentity;
use Foundry\Core\Entities\Contracts\HasReference;
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
class Folder extends Entity implements HasIdentity, IsSoftDeletable, IsNestedTreeable, HasReference {

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
	 * @var File
	 */
	protected $file;

	/**
	 * @var boolean
	 */
	protected $is_file = false;

	public function __construct( array $properties = [] ) {
		parent::__construct( $properties );
		$this->setUuid();
	}

	/**
	 * @return File
	 */
	public function getFile() {
		return $this->file;
	}

	/**
	 * @param File $file
	 */
	public function setFile( File $file ): void {
		$this->file = $file;
		$this->name = $file->original_name;
		$this->is_file = true;
	}

	/**
	 * Determine if the folder is a directory
	 *
	 * @return bool
	 */
	public function isDirectory()
	{
		return !($this->file);
	}

	public function isFile()
	{
		return !!($this->file);
	}


}