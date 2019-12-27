<?php

namespace Foundry\System\Entities\Traits;

use Doctrine\ORM\EntityNotFoundException;
use Foundry\Core\Entities\Contracts\IsNode;
use Foundry\System\Entities\Node;
use LaravelDoctrine\ORM\Facades\EntityManager;

/**
 * Trait NodeReferenceable
 *
 * Allows entities to reference an existing Node in the system
 *
 * This is used on entities which would normally contain a morphable property
 *
 * Using this trait allows the system to better control referential integrity but also maintain a morphable quality
 *
 * @package Foundry\System\Entities\Traits
 */
trait NodeReferenceable
{
    /**
     * @var IsNode
     */
    protected $node;

	/**
	 * @param IsNode|integer $node
	 *
	 * @throws EntityNotFoundException
	 */
    public function setNode($node): void
    {
    	if (is_integer($node)) {
    		$node = EntityManager::getRepository(Node::class)->find($node);
    		if (!$node) {
    			throw new EntityNotFoundException(__('Node not found'));
		    }
	    }
        $this->node = $node;
    }

	/**
	 * @return IsNode|null
	 */
    public function getNode() : ?IsNode
    {
        return $this->node;
    }

	/**
	 * Gets the referenced object attached
	 *
	 * @return \Foundry\Core\Entities\Entity|null|object
	 */
    public function getEntity()
    {
    	if ($node = $this->getNode()) {
    		return $node->getEntity();
	    }
	    return null;
    }

}