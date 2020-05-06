<?php

namespace Foundry\System\Http\Controllers;

use Foundry\Core\Requests\Response;
use Foundry\System\Http\Requests\Roles\AddRoleRequest;
use Foundry\System\Http\Requests\Roles\BrowseRolesRequest;
use Foundry\System\Http\Requests\Roles\DeleteRoleRequest;
use Foundry\System\Http\Requests\Roles\EditRolePermissionsRequest;
use Foundry\System\Http\Requests\Roles\EditRoleRequest;
use Foundry\System\Http\Requests\Roles\ReadRolePermissionsRequest;
use Foundry\System\Http\Resources\Role;
use Foundry\System\Inputs\Role\RoleInput;
use Foundry\System\Inputs\SearchFilterInput;
use Foundry\System\Repositories\RoleRepository;
use Foundry\System\Services\RoleService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RolesController extends Controller
{

    public function browse(BrowseRolesRequest $request)
    {

        $inputs = SearchFilterInput::make($request->all());

        $inputs->validate();

        $page = $request->input('page', 1);
        $limit = $request->input('limit', 20);

        return RoleService::service()
            ->browse($inputs, $page, $limit )
            ->asResource(Role::class, true)
            ->toJsonResponse($request)
            ;
    }

    public function add(AddRoleRequest $request)
    {
        $inputs = RoleInput::make($request->all());

        if ($view = $inputs->viewOrValidate($request)) {
            return Response::success($view)->toJsonResponse($request);
        }

        return RoleService::service()->add($inputs)->toJsonResponse($request);
    }

    public function edit(EditRoleRequest $request)
    {
        $inputs = RoleInput::make($request->all());

        if ($view = $inputs->viewOrValidate($request)) {
            return Response::success($view)->toJsonResponse($request);
        }

        return RoleService::service()->edit($inputs, $request->getEntity())->toJsonResponse($request);
    }

    public function delete(DeleteRoleRequest $request)
    {
        return RoleService::service()->delete($request->getEntity())->toJsonResponse($request);
    }

    public function editPermissions(EditRolePermissionsRequest $request)
    {
        return RoleService::service()->syncPermissions($request->input('permissions'));
    }

    public function readPermissions(ReadRolePermissionsRequest $request)
    {
        if ($role = $request->input('role')) {
            $role = RoleRepository::repository()->find($role);
            if (!$role) {
                throw new NotFoundHttpException('Role not found');
            }
        }
        return RoleService::service()->permissionsWithRolesForGuard($request->route('guard'), $role)->toJsonResponse($request);

    }


}
