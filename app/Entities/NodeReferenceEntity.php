<?php

namespace Foundry\System\Entities;

use Foundry\Core\Entities\Contracts\HasNode;
use Foundry\Core\Entities\Entity;
use Foundry\System\Entities\Traits\NodeReferenceable;

abstract class NodeReferenceEntity extends Entity implements HasNode {

	use NodeReferenceable;

}