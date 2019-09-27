<?php

namespace Foundry\System\Services;

use Foundry\Core\Inputs\Inputs;
use Foundry\Core\Requests\Response;
use Foundry\Core\Services\BaseService;
use Foundry\System\Models\PickList;
use Foundry\System\Models\PickListItem;
use Foundry\System\Inputs\PickListItem\PickListEditItemInput;
use Foundry\System\Inputs\PickListItem\PickListItemInput;
use Foundry\System\Repositories\PickListItemRepository;
use Foundry\System\Repositories\PickListRepository;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;

class PickListItemService extends BaseService {

	public function browse( PickList $picklist, Inputs $inputs, $page = 1, $perPage = 20 ): Paginator {
		return PickListItemRepository::repository()->filter(function(Builder $qb) use ($inputs, $picklist) {

			$qb
				->select(
					'picklist_items.id',
					'picklist_items.label',
					'picklist_items.identifier',
					'picklist_items.description',
					'picklist_items.sequence',
					'picklist_items.status',
					'picklists.default_item as default_id'
				)
				->join('picklists', 'picklists.id', '=', 'picklist_items.picklist_id')
				->orderBy('label', 'ASC')
			;

			if ($search = $inputs->value('search')) {
				$qb->where('picklist_items.label', 'like', "%" . $search . "%");
			}

			$qb->where('picklist_items.picklist_id', $picklist->getKey());

			return $qb;

		}, $page, $perPage);
	}

	/**
	 * @param PickListItemInput|Inputs $input
	 *
	 * @return Response
	 */
	public function add(PickListItemInput $input) : Response
	{
        $pickListItem = new PickListItem($input->values());
        if ($id = $input->value('picklist')) {
	        $pickListItem->picklist = $id;
        }

		PickListItemRepository::repository()->save($pickListItem);

		if ($input->value('default_item') && $pickListItem->picklist) {
			$pickListItem->picklist->default_item = $pickListItem->getKey();
			PickListRepository::repository()->save($pickListItem->picklist);
		}

		PickListRepository::repository()->clearCachedSelectableList($pickListItem->picklist->identifier);

        return Response::success($pickListItem);
	}

	/**
	 * @param PickListEditItemInput|Inputs $input
	 * @param PickListItem $pickListItem
	 *
	 * @return Response
	 */
	public function edit(PickListEditItemInput $input, PickListItem $pickListItem) : Response
	{
		$default_item = $pickListItem->picklist->default_item;

		$pickListItem->fill($input->values());
		PickListItemRepository::repository()->save($pickListItem);

		if ($pickListItem->picklist) {
			$should = $input->value('default_item');
			if (!$should && ($default_item === $pickListItem->getKey())) {
				$pickListItem->picklist->default_item = null;
			} elseif ($should) {
				if ($pickListItem->status == true) {
					$pickListItem->picklist->default_item = $pickListItem->getKey();
				}
			}
			PickListRepository::repository()->save($pickListItem->picklist);
		}

		PickListRepository::repository()->clearCachedSelectableList($pickListItem->picklist->identifier);
		$pickListItem->makeVisible('picklist');

		return Response::success($pickListItem);
	}


}