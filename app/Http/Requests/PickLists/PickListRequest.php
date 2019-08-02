<?php

namespace Foundry\System\Http\Requests\PickLists;

use Foundry\Core\Requests\Contracts\EntityRequestInterface;
use Foundry\Core\Requests\FormRequest;
use Foundry\Core\Requests\Traits\HasEntity;
use Foundry\System\Entities\PickList;
use LaravelDoctrine\ORM\Facades\EntityManager;

abstract class PickListRequest extends FormRequest implements EntityRequestInterface
{
	use HasEntity;

	/**
	 * @param mixed $id
	 *
	 * @return null|PickList|object
	 */
	public function findEntity($id)
	{
		return EntityManager::getRepository(PickList::class)->findOneBy(['id' => $id]);
	}

}
