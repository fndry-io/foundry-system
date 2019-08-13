<?php

namespace Foundry\System\Repositories;

use Doctrine\ORM\Mapping;
use Foundry\Core\Entities\Contracts\HasIdentity;
use Foundry\Core\Repositories\EntityRepository;
use Foundry\System\Entities\Contracts\HasFolder;
use Foundry\System\Entities\Folder;
use Gedmo\Tree\Entity\Repository\NestedTreeRepository;
use LaravelDoctrine\ORM\Facades\EntityManager;

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
	 *
	 *
	 * @param HasIdentity $entity
	 * @param string|null $name
	 * @param Folder|null $parent
	 * @param bool|null $create
	 *
	 * @return bool|Folder|null|object
	 * @throws \ReflectionException
	 */
	public function getRootFolderByEntity(HasIdentity $entity, string $name = null, Folder $parent = null, bool $create = null)
	{
		if ($entity instanceof \Doctrine\ORM\Proxy\Proxy) {
			$class = get_parent_class($entity);
		} else {
			$class = get_class($entity);
		}

		$folder = $this->findOneBy(['reference_type' => $class, 'reference_id' => $entity->getId()]);
		if (!$folder && $create) {
			$folder = new Folder();
			if (empty($name)){
				$name = (new \ReflectionClass($entity))->getShortName() . ' - ' . $entity->getId();
			}
			$folder->name = $name;
			$folder->reference_type = $class;
			$folder->reference_id = $entity->getId();
			if ($parent) {
				$folder->setParent($parent);
			}
			EntityManager::persist($folder);
			EntityManager::flush();

			if ($entity instanceof HasFolder) {
				$entity->setFolder($folder);
			}
		}
		return $folder;
	}

}