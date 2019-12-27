<?php

namespace Foundry\System\Inputs\Folder;

use Foundry\Core\Inputs\Inputs;
use Foundry\Core\Inputs\Types\HiddenInputType;
use Foundry\Core\Support\InputTypeCollection;
use Foundry\System\Inputs\Types\Folder;
use Foundry\System\Inputs\Types\ReferenceId;
use Foundry\System\Inputs\Types\ReferenceType;

/**
 * Class FolderEntityInput
 *
 * @package Foundry\System\Inputs
 *
 */
class FolderEntityInput extends Inputs {


	public function types() : InputTypeCollection
	{
		return InputTypeCollection::fromTypes([
			(new HiddenInputType('create')),
			ReferenceType::input(),
			ReferenceId::input(),
			Folder::input()->setName('parent')
		]);
	}

}