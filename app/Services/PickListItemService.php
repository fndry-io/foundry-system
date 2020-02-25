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
use Modules\Unlimited\Faqs\Repositories\FaqRepository;

class PickListItemService extends BaseService {


    /**
     * Read a Faq
     *
     * @param $faq
     * @return Response
     * @throws \Exception
     */
    public function read($picklistItem)
    {
        return Response::success(PickListItemRepository::repository()->read($picklistItem));
    }

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
	public function browse(IsPickList $pick_list, Inputs $inputs, $page = 1, $perPage = 20, $sortBy = null, $sortDesc = null): Response
	{
		return Response::success(PickListItemRepository::repository()->browse($pick_list, $inputs->values(), $page, $perPage, $sortBy, $sortDesc));
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
	 * @param PickListItemInput|Inputs $input
	 * @param IsPickListItem $pickListItem
	 *
	 * @return Response
	 */
	public function edit(PickListItemInput $input, IsPickListItem $pickListItem) : Response
	{
		$pickListItem = PickListItemRepository::repository()->update($pickListItem, $input->values());
		if ($pickListItem) {
			return Response::success($pickListItem);
		} else {
			return Response::error(__('Unable to edit pick list item'), 500);
		}
	}

    /**
     * @param IsPickListItem $pickListItem
     * @return Response
     * @throws \Exception
     */
    public function delete(IsPickListItem $pickListItem) : Response
    {
        if (PickListItemRepository::repository()->delete($pickListItem)) {
            return Response::success();
        } else {
            return Response::error(__('Unable to delete pick list item'), 500);
        }
    }


}
