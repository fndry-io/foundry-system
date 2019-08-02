<?php

namespace Foundry\System\Repositories;

use Foundry\Core\Repositories\EntityRepository;


class PickListItemRepository  extends EntityRepository {

	public function getAlias(): string {
		return 'picklist_item';
	}

    public function getLabelList() {

        $qb = $this->query();
        $qb->select('picklist_item.id', 'picklist_item.name');
        return $qb->getQuery()->getArrayResult();
    }

}