<?php

namespace Foundry\System\Http\Requests\PickListItems;

use Foundry\Core\Requests\Contracts\EntityRequestInterface;
use Foundry\Core\Requests\FormRequest;
use Foundry\Core\Requests\Traits\HasEntity;
use Foundry\System\Models\PickListItem;
use Foundry\System\Repositories\PickListItemRepository;

abstract class PickListItemRequest extends FormRequest implements EntityRequestInterface
{
	use HasEntity;

	/**
	 * @param mixed $id
	 *
	 * @return null|PickListItem|object
	 */
	public function findEntity($id)
	{
		return PickListItemRepository::repository()->find($id);
	}

}
