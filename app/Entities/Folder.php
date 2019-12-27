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
use LaravelDoctrine\ORM\Facades\EntityManager;

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
		'parent'
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

	public function getLeft()
	{
		return $this->lft;
	}

	public function getRight()
	{
		return $this->rgt;
	}

	public function onBeforeRemove()
	{
		if ($this->isDeleted()) {
			$this->deleteFiles = [];
			foreach(EntityManager::getRepository(self::class)->getTreeRepository()->getChildren($this) as $child) {
				if ($child->isFile()) {
					$this->deleteFiles[] = $child->file;
				}
			}
		}
	}

	public function onAfterRemove()
	{
		if ($this->deleteFiles) {
			foreach ($this->deleteFiles as $file) {
				EntityManager::remove($file);
				$file->onAfterRemove();
			}
		}
	}

	public function getEntity()
	{
		// TODO: Implement getEntity() method.
	}

	public function setEntity($entity)
	{
		// TODO: Implement setEntity() method.
	}
}