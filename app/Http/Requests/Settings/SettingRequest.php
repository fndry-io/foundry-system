<?php

namespace Foundry\System\Http\Requests\Settings;

use Foundry\Core\Repositories\SettingRepository;
use Foundry\Core\Requests\Contracts\EntityRequestInterface;
use Foundry\Core\Requests\FoundryFormRequest;
use Foundry\Core\Requests\Traits\HasEntity;
use Foundry\System\Models\Setting;

/**
 * Class RoleRequest
 *
 *
 * @package Foundry\System\Http\Requests\Roles
 */
abstract class SettingRequest extends FoundryFormRequest implements EntityRequestInterface
{
	use HasEntity;

	/**
	 * @param mixed $id
	 *
	 * @return null|Setting|object
	 */
	public function findEntity($id)
	{
		return SettingRepository::repository()->find($id);
	}

}
