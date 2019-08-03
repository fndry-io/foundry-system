<?php

namespace Foundry\System\Inputs\Folder;

use Foundry\Core\Inputs\Inputs;
use Foundry\Core\Support\InputTypeCollection;
use Foundry\System\Inputs\Types\File;

class FolderFileInput extends Inputs {

	/**
	 * The types to associate with the input
	 *
	 * @return InputTypeCollection
	 */
	function types(): InputTypeCollection {
		return InputTypeCollection::fromTypes([
			File::input()
		]);
	}
}