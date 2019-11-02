<?php

namespace Foundry\System\Http\Requests\Roles;

use Foundry\Core\Requests\FormRequest;
use Foundry\Core\Requests\Response;
use Foundry\System\Inputs\Role\Types\Guard;
use Foundry\System\Repositories\RoleRepository;
use Foundry\System\Services\RoleService;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ReadRolePermissionsRequest extends FormRequest
{
	public static function name(): String {
		return 'foundry.system.roles.permissions';
	}

	/**
     * Determine if the role is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return ($this->user() && $this->user()->can('browse permissions'));
    }

    public function rules()
    {
        return [];
    }

    /**
     * @return Response
     * @throws \Exception
     */
    public function handle() : Response
    {
        if ($role = $this->input('role')) {
            $role = RoleRepository::repository()->find($role);
            if (!$role) {
                throw new NotFoundHttpException('Role not found');
            }
        }
	    return RoleService::service()->permissionsWithRolesForGuard($this->route('guard'), $role);
    }

}
