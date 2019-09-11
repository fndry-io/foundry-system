<?php

namespace Foundry\System\Repositories;

use Foundry\Core\Repositories\EntityRepository;
use Foundry\System\Entities\PickList;
use Foundry\System\Entities\PickListItem;
use LaravelDoctrine\ORM\Facades\EntityManager;


class PickListItemRepository  extends EntityRepository {

	public function getAlias(): string {
		return 'picklist_item';
	}

	/**
	 * @return \Doctrine\Common\Persistence\ObjectRepository|PickListItemRepository
	 */
	static function get() {
		return EntityManager::getRepository(PickListItem::class);
	}

    public function getLabelList(PickList $pick_list, $name = null) {

        $qb = $this->query();
        $qb->select('picklist_item.id', 'picklist_item.label');
        $qb->join('picklist_item.picklist', 'picklist');

	    $where = $qb->expr()->andX();

	    $where->add($qb->expr()->eq('picklist_item.picklist', $pick_list->getId()));

	    if ($name) {
		    $where->add($qb->expr()->like('picklist_item.label', ':name'));
		    $qb->setParameter('name', "%$name%");
	    }

	    if ($where->count() > 0) {
		    $qb->where($where);
	    }

        return $qb->getQuery()->getArrayResult();
    }

}