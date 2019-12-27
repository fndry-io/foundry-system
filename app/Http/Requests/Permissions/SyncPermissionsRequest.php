<?php

namespace Foundry\System\Http\Requests\Permissions;

use Foundry\Core\Requests\FormRequest;
use Foundry\Core\Requests\Response;
use Foundry\System\Events\SyncPermissions;

class SyncPermissionsRequest extends FormRequest
{
	public static function name(): String {
		return 'foundry.system.permissions.sync';
	}

	/**
	 * Determine if the role is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
        return ($this->user() && $this->user()->can('edit permissions'));
	}

	/**
	 * Handle the request
	 *
	 * @return Response
	 */
	public function handle() : Response
	{
        event(new SyncPermissions());
		return Response::success();
	}
}
