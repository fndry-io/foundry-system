<?php

namespace Foundry\System\Entities\Traits;

use Foundry\Core\Entities\Contracts\IsNode;
use Foundry\System\Entities\Node;
use LaravelDoctrine\ORM\Facades\EntityManager;

/**
 * Trait Nodeable
 *
 * Allows an entity to be a Node in the system to which content can be connected or associated
 *
 * @package Foundry\System\Entities\Traits
 */
trait Nodeable
{
    /**
     * @var IsNode
     */
    protected $node;

	/**
	 * @param IsNode|integer $node
	 */
    public function setNode($node): void
    {
    	if (is_string($node)) {
    		$node = EntityManager::getRepository(Node::class)->find($node);
	    }
        $this->node = $node;
    }

	/**
	 * @return IsNode|null
	 */
    public function getNode(): ?IsNode
    {
    	if (!$this->node) {
    		$this->node = $this->makeNode();
	    }
        return $this->node;
    }

	/**
	 * @return IsNode|null
	 */
    abstract function getParentNode() : ?IsNode;

	/**
	 * @return IsNode
	 */
    public function makeNode(): IsNode
    {
    	$node = new Node([]);
    	$node->attachEntity($this);

    	if ($parent = $this->getParentNode()) {
    		$node->setParent($parent);
	    }

    	EntityManager::persist($node);
	    EntityManager::flush($node);
	    return $node;
    }


}