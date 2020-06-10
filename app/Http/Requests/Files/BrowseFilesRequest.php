<?php

namespace Foundry\System\Http\Requests\Files;

use Foundry\Core\Requests\FoundryFormRequest;
use Foundry\Core\Requests\Traits\HasReference;

class BrowseFilesRequest extends FoundryFormRequest
{
	use HasReference;

	/**
	 * Determine if the Folder is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
        return ($this->user() && $this->user()->can('system.folders.read'));
	}
}
