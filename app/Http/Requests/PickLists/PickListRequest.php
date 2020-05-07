<?php

namespace Foundry\System\Http\Requests\PickLists;

use Foundry\Core\Entities\Contracts\IsPickList;
use Foundry\Core\Requests\Contracts\EntityRequestInterface;
use Foundry\Core\Requests\FormRequest;
use Foundry\Core\Requests\FoundryFormRequest;
use Foundry\Core\Requests\Traits\HasEntity;
use Foundry\System\Models\PickList;
use Foundry\System\Repositories\PickListRepository;

/**
 * Class PickListRequest
 *
 * @method IsPickList getEntity()
 *
 * @package Foundry\System\Http\Requests\PickLists
 */
abstract class PickListRequest extends FoundryFormRequest implements EntityRequestInterface
{
	use HasEntity;

	/**
	 * @param mixed $id
	 *
	 * @return null|PickList|object
	 */
	public function findEntity($id)
	{
		return PickListRepository::repository()->find($id);
	}

}
