<?php

namespace Foundry\System\Http\Requests\Roles;

use Foundry\Core\Requests\FormRequest;
use Foundry\Core\Requests\Response;
use Foundry\System\Services\RoleService;

class EditRolePermissionsRequest extends FormRequest
{
	public static function name(): String {
		return 'foundry.system.roles.permissions.edit';
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

    public function rules()
    {
        return [
            'permissions' => 'required'
        ];
    }

    /**
     * @return Response
     * @throws \Exception
     */
    public function handle() : Response
    {
	    return RoleService::service()->syncPermissions($this->input('permissions'));
    }

}
