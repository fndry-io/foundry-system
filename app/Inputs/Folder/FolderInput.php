<?php

namespace Foundry\System\Inputs\Folder;

use Foundry\Core\Inputs\Inputs;
use Foundry\Core\Support\InputTypeCollection;
use Foundry\System\Inputs\Folder\Types\Name;
use Foundry\System\Inputs\Types\Folder;
use Foundry\System\Inputs\Types\ReferenceId;
use Foundry\System\Inputs\Types\ReferenceType;

/**
 * Class FolderInput
 *
 * @package Foundry\System\Inputs
 *
 */
class FolderInput extends Inputs {


	public function types() : InputTypeCollection
	{
		return InputTypeCollection::fromTypes([
			Name::input(),
			ReferenceType::input(),
			ReferenceId::input(),
			Folder::input()->setName('parent')
		]);
	}

}