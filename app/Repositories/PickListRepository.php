<?php

namespace Foundry\System\Repositories;

use Foundry\Core\Repositories\EntityRepository;


class PickListRepository  extends EntityRepository {

	public function getAlias(): string {
		return 'picklist';
	}


    public function getLabelList() {

        $qb = $this->query();
        $qb->select('picklist.id', 'picklist.name');
        return $qb->getQuery()->getArrayResult();
    }

}