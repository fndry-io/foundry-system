<?php

namespace Foundry\System\Http\Requests\Settings;

class EditSettingRequest extends SettingRequest
{
	/**
	 * Determine if the role is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
        return ($this->user() && $this->user()->can('system.settings.manage'));
	}

}
