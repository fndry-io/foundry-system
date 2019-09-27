<?php

namespace Foundry\System\Repositories;

use Foundry\Core\Repositories\ModelRepository;
use Foundry\System\Models\PickList;
use Foundry\System\Models\PickListItem;

class PickListItemRepository extends ModelRepository {

    public function getLabelList(PickList $pick_list, $name = null) {

        $qb = $this->query();
        $qb->select('id', 'label');
	    $qb->where('picklist_id', $pick_list->getKey());

	    if ($name) {
		    $qb->where('label', 'like', "%$name%");
	    }

        return $qb->get();
    }

	/**
	 * Returns the class name of the object managed by the repository.
	 *
	 * @return string|PickListItem
	 */
	public function getClassName()
	{
		return PickListItem::class;
	}
}