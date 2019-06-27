<?php

namespace Foundry\System\Http\Requests\Addresses;

use Foundry\Core\Requests\Contracts\EntityRequestInterface;
use Foundry\Core\Requests\FormRequest;
use Foundry\Core\Requests\Traits\HasEntity;
use Foundry\System\Entities\Address;
use LaravelDoctrine\ORM\Facades\EntityManager;

abstract class AddressRequest extends FormRequest implements EntityRequestInterface
{
	use HasEntity;

	/**
	 * @param mixed $id
	 *
	 * @return null|Address|object
	 */
	public function findEntity($id)
	{
		return EntityManager::getRepository(Address::class)->find($id);
	}

}
