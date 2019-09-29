<?php

namespace Foundry\System\Services;

use Foundry\Core\Entities\Contracts\IsPickList;
use Foundry\Core\Entities\Contracts\IsPickListItem;
use Foundry\Core\Inputs\Inputs;
use Foundry\Core\Requests\Response;
use Foundry\Core\Services\BaseService;
use Foundry\System\Inputs\PickListItem\PickListEditItemInput;
use Foundry\System\Inputs\PickListItem\PickListItemInput;
use Foundry\System\Repositories\PickListItemRepository;
use Illuminate\Contracts\Pagination\Paginator;

class PickListItemService extends BaseService {

	/**
	 * Browse the list of companies
	 *
	 * @param IsPickList $pick_list
	 * @param Inputs $inputs
	 * @param int $page
	 * @param int $perPage
	 *
	 * @return Response The data key will contain an instance of Paginator
	 * @see Paginator
	 */
	public function browse(IsPickList $pick_list, Inputs $inputs, $page = 1, $perPage = 20): Response
	{
		return Response::success(PickListItemRepository::repository()->browse($pick_list, $inputs->values(), $page, $perPage));
	}

	/**
	 * @param PickListItemInput|Inputs $input
	 *
	 * @return Response
	 */
	public function add(PickListItemInput $input) : Response
	{
		$pickListItem = PickListItemRepository::repository()->insert($input->values());
		if ($pickListItem) {
			return Response::success($pickListItem);
		} else {
			return Response::error(__('Unable to add pick list item'), 500);
		}
	}

	/**
	 * @param PickListEditItemInput|Inputs $input
	 * @param IsPickListItem $pickListItem
	 *
	 * @return Response
	 */
	public function edit(PickListEditItemInput $input, IsPickListItem $pickListItem) : Response
	{
		$pickListItem = PickListItemRepository::repository()->update($pickListItem, $input->values());
		if ($pickListItem) {
			return Response::success($pickListItem);
		} else {
			return Response::error(__('Unable to edit pick list item'), 500);
		}
	}


}