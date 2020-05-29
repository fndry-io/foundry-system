<?php

namespace Foundry\System\Http\Requests\Settings;

use Foundry\Core\Requests\FoundryFormRequest;

class BrowseSettingsRequest extends FoundryFormRequest
{
	/**
     * Determine if the role is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return ($this->user() && $this->user()->can('browse settings'));
    }

}
