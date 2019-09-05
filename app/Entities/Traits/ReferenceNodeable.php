<?php

namespace Foundry\System\Entities\Traits;

use Foundry\Core\Entities\Contracts\HasIdentity;
use Foundry\System\Entities\Contracts\HasNode;
use Foundry\System\Entities\Node;

trait ReferenceNodeable
{
	use Nodeable;

	/**
	 * Attach a reference to the entity
	 *
	 * @param HasIdentity $reference
	 *
	 * @throws \Exception
	 */
	public function attachReference( HasIdentity $reference)
	{
		if (!$reference instanceof HasNode) {
			throw new \Exception(sprintf('Attached reference %s must implement HasNode', get_entity_class($reference)));
		}
		$this->setReferenceType(get_entity_class($reference));
		$this->setReferenceId($reference->getId());
		$this->node = $reference->getNode();
	}

	/**
	 * @return Node
	 */
	public function makeNode(): Node
	{
		if ($reference = $this->getReference()) {
			return $reference->getNode();
		}
		return null;
	}

	public function getParentNode()
	{
		if ($reference = $this->getReference()) {
			return $reference->getNode()->getParent();
		}
		return null;
	}

}