<?php

namespace Foundry\System\Http\Requests\PickListItems;

use Foundry\Core\Entities\Contracts\IsPickListItem;
use Foundry\Core\Requests\Contracts\EntityRequestInterface;
use Foundry\Core\Requests\FormRequest;
use Foundry\Core\Requests\Traits\HasEntity;
use Foundry\System\Models\PickListItem;
use Foundry\System\Repositories\PickListItemRepository;

/**
 * Class PickListItemRequest
 *
 * @method IsPickListItem getEntity()
 *
 * @package Foundry\System\Http\Requests\PickListItems
 */
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
