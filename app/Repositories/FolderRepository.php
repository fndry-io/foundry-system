<?php

namespace Foundry\System\Repositories;

use Doctrine\ORM\Mapping;
use Foundry\Core\Entities\Contracts\HasIdentity;
use Foundry\Core\Repositories\EntityRepository;
use Foundry\System\Entities\Folder;
use Gedmo\Tree\Entity\Repository\NestedTreeRepository;

class FolderRepository extends EntityRepository {

	/**
	 * @var NestedTreeRepository
	 */
	protected $tree;

	public function __construct( $em, Mapping\ClassMetadata $class ) {
		parent::__construct( $em, $class );
		$this->tree = new NestedTreeRepository( $em, $class );
	}

	public function getAlias(): string {
		return 'folder';
	}

	/**
	 * @return NestedTreeRepository
	 */
	public function getTreeRepository()
	{
		return $this->tree;
	}

	/**
	 * @param HasIdentity $entity
	 *
	 * @return Folder|null|object
	 */
	public function getFolderByEntity(HasIdentity $entity)
	{
		return $this->findOneBy(['reference_type' => get_class($entity), 'reference_id' => $entity->getId()], ['name' => 'ASC']);
	}

}