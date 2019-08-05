<?php

namespace Foundry\System\Services;

use Foundry\Core\Entities\Entity;
use Foundry\Core\Inputs\Inputs;
use Foundry\Core\Requests\Response;
use Foundry\Core\Services\BaseService;
use Foundry\Core\Services\Traits\HasRepository;
use Foundry\System\Entities\PickList;
use Foundry\System\Inputs\PickList\PickListInput;
use Foundry\System\Repositories\PickListRepository;
use LaravelDoctrine\ORM\Facades\EntityManager;

class PickListService extends BaseService {

	use HasRepository;

	/**
	 * @var PickListRepository
	 */
	protected $repository;

	public function __construct() {
		$this->setRepository(EntityManager::getRepository(PickList::class));
	}

	/**
	 * @param PickListInput|Inputs $input
	 *
	 * @return Response
	 */
	public function add(PickListInput $input) : Response
	{
		$pickList = new PickList($input->inputs());
		$this->repository->save($pickList);
		return Response::success($pickList);
	}

	/**
	 * @param PickListInput|Inputs $input
	 * @param PickList|Entity $pickList
	 *
	 * @return Response
	 */
	public function edit(PickListInput $input, PickList $pickList) : Response
	{
		$pickList->fill($input);
		$this->repository->save($pickList);
		$this->repository->clearCachedSelectableList($pickList->identifier);
		return Response::success($pickList);
	}

}