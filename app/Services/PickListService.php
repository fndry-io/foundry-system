<?php

namespace Foundry\System\Services;

use Foundry\Core\Entities\Contracts\IsPickList;
use Foundry\Core\Inputs\Inputs;
use Foundry\Core\Requests\Response;
use Foundry\Core\Services\BaseService;
use Foundry\System\Inputs\PickList\PickListInput;
use Foundry\System\Repositories\PickListRepository;

class PickListService extends BaseService {

	/**
	 * @param Inputs $inputs
	 * @param int $page
	 * @param int $perPage
	 *
	 * @return Response
	 */
	public function browse(Inputs $inputs, $page = 1, $perPage = 20) : Response
	{
		return Response::success(PickListRepository::repository()->browse($inputs->values(), $page, $perPage));
	}

	/**
	 * @param PickListInput|Inputs $input
	 *
	 * @return Response
	 */
	public function add(PickListInput $input) : Response
	{
		$pickListItem = PickListRepository::repository()->insert($input->values());
		if ($pickListItem) {
			return Response::success($pickListItem);
		} else {
			return Response::error(__('Unable to add pick list item'), 500);
		}
	}

	/**
	 * @param PickListInput|Inputs $input
	 * @param IsPickList $pickListItem
	 *
	 * @return Response
	 */
	public function edit(PickListInput $input, IsPickList $pickListItem) : Response
	{
		$pickListItem = PickListRepository::repository()->update($pickListItem, $input->values());
		if ($pickListItem) {
			return Response::success($pickListItem);
		} else {
			return Response::error(__('Unable to edit pick list item'), 500);
		}
	}

}
