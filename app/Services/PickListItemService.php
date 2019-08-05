<?php

namespace Foundry\System\Services;

use Foundry\Core\Entities\Entity;
use Foundry\Core\Inputs\Inputs;
use Foundry\Core\Requests\Response;
use Foundry\Core\Services\BaseService;
use Foundry\Core\Services\Traits\HasRepository;
use Foundry\System\Entities\PickList;
use Foundry\System\Entities\PickListItem;
use Foundry\System\Inputs\PickListItem\PickListEditItemInput;
use Foundry\System\Inputs\PickListItem\PickListItemInput;
use LaravelDoctrine\ORM\Facades\EntityManager;

class PickListItemService extends BaseService {

	use HasRepository;

	public function __construct() {
		$this->setRepository(EntityManager::getRepository(PickListItem::class));
	}

	/**
	 * @param PickListItemInput|Inputs $input
	 *
	 * @return Response
	 */
	public function add(PickListItemInput $input) : Response
	{
        $pickListItem = new PickListItem($input->inputs());
        if ($id = $input->input('picklist')) {
            if ($picklist = EntityManager::getRepository(PickList::class)->find($id)) {
                $pickListItem->picklist = $picklist;
            }
        }

		$this->repository->save($pickListItem);

		if ($input->input('default_item') && $pickListItem->picklist) {
			$pickListItem->picklist->default_item = $pickListItem->getId();
			EntityManager::persist($pickListItem->picklist);
		}

		EntityManager::getRepository(PickList::class)->clearCachedSelectableList($pickListItem->picklist->identifier);

        $this->repository->flush();

        return Response::success($pickListItem);
	}

	/**
	 * @param PickListEditItemInput|Inputs $input
	 * @param PickListItem|Entity $pickListItem
	 *
	 * @return Response
	 */
	public function edit(PickListEditItemInput $input, PickListItem $pickListItem) : Response
	{
		$pickListItem->fill($input);
		$this->repository->save($pickListItem);

		if ($input->input('default_item') && $pickListItem->picklist) {
			$pickListItem->picklist->default_item = $pickListItem->getId();
			EntityManager::persist($pickListItem->picklist);
		}

		EntityManager::getRepository(PickList::class)->clearCachedSelectableList($pickListItem->picklist->identifier);

		$this->repository->flush();

		return Response::success($pickListItem);
	}


}