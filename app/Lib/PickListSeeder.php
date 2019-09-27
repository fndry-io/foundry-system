<?php

namespace Foundry\System\Lib;

use Foundry\System\Models\PickList;
use Foundry\System\Models\PickListItem;
use Foundry\System\Repositories\PickListItemRepository;
use Foundry\System\Repositories\PickListRepository;
use Illuminate\Support\Arr;

class PickListSeeder {

	static public function seed($lists)
	{
		foreach ($lists as $list) {

			$picklist = new PickList();
			$picklist->label = Arr::get($list, 'label');
			if ($identifier = Arr::get($list, 'identifier', null)) {
				$picklist->identifier = $identifier;
			}
			if ($is_tag = Arr::get($list, 'is_tag', false)) {
				$picklist->is_tag = $is_tag;
			}

			if ($exists = PickListRepository::repository()->findOneBy(['identifier' => $picklist->identifier])) {
				$picklist = $exists;
				PickListRepository::repository()->clearCachedSelectableList($picklist->identifier);
			} else {
				PickListRepository::repository()->save($picklist);
			}

			foreach ($list['items'] as $key => $item) {

				if (!is_array($item)) {
					$item = [
						'label' => $item,
						'identifier' => $key
					];
				}

				$picklistItem = new PickListItem();
				$picklistItem->picklist = $picklist;
				$picklistItem->label = Arr::get($item, 'label');
				if ($identifier = Arr::get($item, 'identifier', null)) {
					$picklistItem->identifier = $identifier;
				}
				$picklistItem->sequence = Arr::get($item, 'sequence', 0);
				$picklistItem->status = Arr::get($item, 'status', true);
				$picklistItem->is_system = Arr::get($item, 'is_system', false);

				if (!PickListItemRepository::repository()->findOneBy(['identifier' => $picklistItem->identifier, 'picklist' => $picklist])) {
					PickListItemRepository::repository()->save($picklistItem);

					if (Arr::get($item, 'is_default')) {
						$picklist->default_item = $picklistItem->getKey();
						PickListRepository::repository()->save($picklist);
					}
				}
			}
		}
	}
	
}