<?php

namespace Foundry\System\Lib;

use Foundry\System\Entities\PickList;
use Foundry\System\Entities\PickListItem;
use Illuminate\Support\Arr;
use LaravelDoctrine\ORM\Facades\EntityManager;

class PickListSeeder {

	static public function seed($lists)
	{
		foreach ($lists as $list) {

			$picklist = new PickList();
			$picklist->name = Arr::get($list, 'name');
			if ($slug = Arr::get($list, 'slug', null)) {
				$picklist->slug = $slug;
			}

			if ($exists = EntityManager::getRepository(PickList::class)->findOneBy(['slug' => $picklist->slug])) {
				$picklist = $exists;
			} else {
				EntityManager::persist($picklist);
				EntityManager::flush();
			}

			foreach ($list['items'] as $key => $item) {

				if (!is_array($item)) {
					$item = [
						'name' => $item,
						'slug' => $key
					];
				}

				$picklistItem = new PickListItem();
				$picklistItem->picklist = $picklist;
				$picklistItem->name = Arr::get($item, 'name');
				if ($slug = Arr::get($item, 'slug', null)) {
					$picklistItem->slug = $slug;
				}
				$picklistItem->sequence = Arr::get($item, 'sequence', 0);
				$picklistItem->status = Arr::get($item, 'status', true);
				$picklistItem->is_system = Arr::get($item, 'is_system', false);

				if (!EntityManager::getRepository(PickListItem::class)->findOneBy(['slug' => $picklistItem->slug, 'picklist' => $picklist])) {
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