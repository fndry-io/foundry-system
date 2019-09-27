<?php

namespace Foundry\System\Http\Requests\Roles;

use Foundry\Core\Requests\Contracts\EntityRequestInterface;
use Foundry\Core\Requests\Response;
use Foundry\System\Services\RoleService;

class DeleteRoleRequest  extends RoleRequest implements EntityRequestInterface
{

	public static function name(): String {
		return 'foundry.system.roles.delete';
	}

	public function authorize()
	{
		return !!($this->user());
	}

	/**
	 * Handle the request
	 *
	 * @return Response
	 * @throws \Exception
	 */
	public function handle() : Response
	{
		return RoleService::service()->delete($this->getEntity());
	}

}
