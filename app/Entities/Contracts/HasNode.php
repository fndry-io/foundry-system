<?php

namespace Foundry\System\Entities\Contracts;

use Foundry\Core\Entities\Contracts\HasIdentity;
use Foundry\System\Entities\Node;

interface HasNode extends HasIdentity
{
    /**
     * @param Node $node
     */
    public function setNode($node): void;

    /**
     * @return Node|null
     */
    public function getNode();

	/**
	 * @return Node
	 */
    public function makeNode();

}