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
use Foundry\System\Repositories\PickListItemRepository;
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
        if ($input->input('default_item') && $input->input('picklist')) {
            $id = $input->input('picklist');
            $picklist = EntityManager::getRepository(PickList::class)->find($id);
            $picklist->default_item=$pickListItem->getId();
            EntityManager::getRepository(PickList::class)->flush($picklist);
        }
        $this->repository->flush($pickListItem);

        return Response::success($pickListItem);
	}

	/**
	 * @param PickListItemInput|Inputs $input
	 * @param PickListItem|Entity $pickListItem
	 *
	 * @return Response
	 */
	public function edit(PickListEditItemInput $input, PickListItem $pickListItem) : Response
	{
		$pickListItem->fill($input);

        if ($id = $input->input('picklist')) {
            if ($picklist = EntityManager::getRepository(PickList::class)->find($id)) {
                $pickListItem->picklist = $picklist;
            }
        }



		$this->repository->save($pickListItem);
		return Response::success($pickListItem);
	}


	public function restore(PickListItem $pickListItem) : Response
	{
		$this->repository->restore($pickListItem);
		return Response::success();
	}

}