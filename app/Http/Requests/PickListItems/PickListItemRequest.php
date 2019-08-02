<?php

namespace Foundry\System\Http\Requests\PickListItems;

use Foundry\Core\Requests\Contracts\EntityRequestInterface;
use Foundry\Core\Requests\FormRequest;
use Foundry\Core\Requests\Traits\HasEntity;
use Foundry\System\Entities\PickListItem;

use LaravelDoctrine\ORM\Facades\EntityManager;

abstract class PickListItemRequest extends FormRequest implements EntityRequestInterface
{
	use HasEntity;

	/**
	 * @param mixed $id
	 *
	 * @return null|Picklist|object
	 */
	public function findEntity($id)
	{
		return EntityManager::getRepository(PickListItem::class)->findOneBy(['id' => $id]);
	}

}
