<?php

namespace Foundry\System\Services;

use Foundry\Core\Entities\Entity;
use Foundry\Core\Inputs\Inputs;
use Foundry\Core\Requests\Response;
use Foundry\Core\Services\BaseService;
use Foundry\Core\Services\Traits\HasRepository;
use Foundry\System\Entities\Address;
use Foundry\System\Inputs\Address\AddressInput;
use Foundry\System\Repositories\AddressRepository;
use LaravelDoctrine\ORM\Facades\EntityManager;

class AddressService extends BaseService {

	use HasRepository;

	public function __construct() {
		$this->setRepository(EntityManager::getRepository(Address::class));
	}

	/**
	 * @param AddressInput|Inputs $input
	 *
	 * @return Response
	 */
	public function add(AddressInput $input) : Response
	{
		$address = new Address($input->inputs());
		$this->repository->save($address);
		return Response::success($address);
	}

	/**
	 * @param AddressInput|Inputs $input
	 * @param Address|Entity $address
	 *
	 * @return Response
	 */
	public function edit(AddressInput $input, Address $address) : Response
	{
		$address->fill($input);
		$this->repository->save($address);
		return Response::success($address);
	}

	/**
	 * Delete a user
	 *
	 * @param Address|Entity $address
	 *
	 * @return Response
	 */
	public function delete(Address $address) : Response
	{
		$this->repository->delete($address);
		return Response::success();
	}

}