<?php

namespace Foundry\System\Lib;

use Foundry\System\Entities\PickList;
use Foundry\System\Entities\PickListItem;
use Foundry\System\Repositories\PickListRepository;
use Illuminate\Support\Arr;
use LaravelDoctrine\ORM\Facades\EntityManager;

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

			if ($exists = EntityManager::getRepository(PickList::class)->findOneBy(['identifier' => $picklist->identifier])) {
				$picklist = $exists;
				PickListRepository::get()->clearCachedSelectableList($picklist->identifier);
			} else {
				EntityManager::persist($picklist);
				EntityManager::flush();
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

				if (!EntityManager::getRepository(PickListItem::class)->findOneBy(['identifier' => $picklistItem->identifier, 'picklist' => $picklist])) {
					EntityManager::persist($picklistItem);

					if (Arr::get($item, 'is_default')) {
						$picklist->default_item = $picklistItem->getId();
						EntityManager::persist($picklist);
					}
				}
			}

			EntityManager::flush();
		}
	}
	
}