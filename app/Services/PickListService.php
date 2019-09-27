<?php

namespace Foundry\System\Services;

use Foundry\Core\Inputs\Inputs;
use Foundry\Core\Requests\Response;
use Foundry\Core\Services\BaseService;
use Foundry\System\Models\PickList;
use Foundry\System\Inputs\PickList\PickListInput;
use Foundry\System\Repositories\PickListRepository;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class PickListService extends BaseService {

	/**
	 * @param Inputs $inputs
	 * @param int $page
	 * @param int $perPage
	 *
	 * @return Paginator
	 */
	public function browse( Inputs $inputs, $page = 1, $perPage = 20 ): Paginator {
		return PickListRepository::repository()->filter(function(Builder $qb) use ($inputs) {
			$qb
				->select('*')
				->orderBy('label', 'ASC');
			return $qb;
		}, $page, $perPage);
	}

	/**
	 * @param PickListInput|Inputs $input
	 *
	 * @return Response
	 */
	public function add(PickListInput $input) : Response
	{
		$pickList = new PickList($input->values());
		PickListRepository::repository()->save($pickList);
		return Response::success($pickList);
	}

	/**
	 * @param PickListInput|Inputs $input
	 * @param PickList|Model $pickList
	 *
	 * @return Response
	 */
	public function edit(PickListInput $input, PickList $pickList) : Response
	{
		$pickList->fill($input->values());
		PickListRepository::repository()->save($pickList);
		PickListRepository::repository()->clearCachedSelectableList($pickList->identifier);
		return Response::success($pickList);
	}

}