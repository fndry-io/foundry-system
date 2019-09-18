<?php

namespace Foundry\System\Entities\Contracts;

use Foundry\Core\Entities\Contracts\HasReference;
use Foundry\Core\Entities\Entity;
use Foundry\Core\Entities\Node;

interface HasReferenceWithNode extends HasReference
{

	/**
	 * @return Node|null
	 */
	public function getNode();

	/**
	 * @return HasNode|Entity|object|null
	 */
	public function getReference();

}