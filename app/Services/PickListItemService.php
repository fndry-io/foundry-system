<?php

namespace Foundry\System\Services;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
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

	public function browse( PickList $pick_list, Inputs $inputs, $page = 1, $perPage = 20 ): Paginator {
		return $this->getRepository()->filter(function(QueryBuilder $qb) use ($inputs, $pick_list) {

			$qb
				->addSelect(
					'picklist_item.id',
					'picklist_item.label',
					'picklist_item.identifier',
					'picklist_item.description',
					'picklist_item.sequence',
					'picklist_item.status',
					'picklist.default_item as default_id'
				)
				->join('picklist_item.picklist', 'picklist')
				->orderBy('picklist_item.label', 'ASC');

			$where = $qb->expr()->andX();

			if ($search = $inputs->input('search')) {
				$where->add($qb->expr()->orX(
					$qb->expr()->like('picklist_item.label', ':search')
				));
				$qb->setParameter(':search', "%" . $search . "%");
			}

			$where->add($qb->expr()->eq('picklist_item.picklist', ':picklist'));
			$qb->setParameter('picklist', $pick_list);

			$qb->where($where);

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
		$default_item = $pickListItem->picklist->default_item;

		$pickListItem->fill($input);
		$this->repository->save($pickListItem);

		if ($pickListItem->picklist) {
			$should = $input->input('default_item');
			if (!$should && ($default_item === $pickListItem->getId())) {
				$pickListItem->picklist->default_item = null;
			} elseif ($should) {
				$pickListItem->picklist->default_item = $pickListItem->getId();
			}
			EntityManager::persist($pickListItem->picklist);
		}

		EntityManager::getRepository(PickList::class)->clearCachedSelectableList($pickListItem->picklist->identifier);

		$this->repository->flush();

		$pickListItem->makeVisible('picklist');

		return Response::success($pickListItem);
	}


}