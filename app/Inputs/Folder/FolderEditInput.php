<?php

namespace Foundry\System\Inputs\Folder;

use Foundry\Core\Inputs\Inputs;
use Foundry\Core\Support\InputTypeCollection;
use Foundry\System\Inputs\Folder\Types\Name;
use Foundry\System\Inputs\Types\ReferenceId;
use Foundry\System\Inputs\Types\ReferenceType;

/**
 * Class FolderEditInput
 *
 * @package Foundry\System\Inputs
 *
 */
class FolderEditInput extends Inputs {


	public function types() : InputTypeCollection
	{
		return InputTypeCollection::fromTypes([
			Name::input()
		]);
	}

}