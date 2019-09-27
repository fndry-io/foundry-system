<?php

namespace Foundry\System\Entities;

use Foundry\Core\Entities\Contracts\HasNode;
use Foundry\Core\Entities\Contracts\IsNodeable;
use Foundry\Core\Entities\Entity;
use Foundry\System\Entities\Traits\Nodeable;
use LaravelDoctrine\ORM\Facades\EntityManager;

/**
 * Class NodeEntity
 *
 * A Node Entity allows as to build a hierachial structure of the central content in the system
 *
 * Doing this allows us to achieve both a hierachy structure as well as a morphable quality in the system
 *
 * A Node Entity represents the hierachial structure of content in the system
 *
 * Content then linked to a Node Entity only needs to store the node of the entity, allowing it to easily link one or
 * many content types to that Node Entity. E.G. Company has many Comments
 *
 * Having the Hiearchial structure (as a Node is a Treeable trait) allows us also to retrieve all associated content
 * of a type within any desired tree level
 *
 * @package Foundry\Core\Entities
 */
abstract class NodeEntity extends Entity implements IsNodeable, HasNode {

	use Nodeable;

	public function onBeforeCreate()
	{
		if (!$this->node) {
			$this->node = new Node();
			if ($parent = $this->getParentNode()) {
				$this->node->setParent($parent);
			}
		}
	}

	public function onAfterCreate()
	{
		$this->node->attachEntity($this);
		EntityManager::persist($this->node);
	}

}