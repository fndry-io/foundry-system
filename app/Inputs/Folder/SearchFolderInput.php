<?php

namespace Foundry\System\Inputs\Folder;

use Foundry\Core\Inputs\Inputs;
use Foundry\Core\Inputs\Types\TextInputType;
use Foundry\Core\Support\InputTypeCollection;
use Foundry\System\Inputs\Types\Folder;

/**
 * Class SearchFilterInput
 *
 * @package Foundry\System\Inputs
 *
 * @property $search
 */
class SearchFolderInput extends Inputs
{
	public function types() : InputTypeCollection
	{
		return InputTypeCollection::fromTypes([
			(new TextInputType('search', 'Search', false)),
            Folder::input()->setName('folder')
		]);
	}
}
