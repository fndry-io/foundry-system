<?php

namespace Foundry\System\Inputs\Types\Traits;

use Foundry\System\Entities\PickList;
use Illuminate\Support\Arr;
use LaravelDoctrine\ORM\Facades\EntityManager;

trait PickListable {

	protected function setPickList($identifier, $valueKey = 'id', $labelKey = 'name')
	{
		$picklist = EntityManager::getRepository(PickList::class)->getCachedSelectableList($identifier);

		$this->setOptions($picklist['items']);
		$this->setTextKey($labelKey);
		$this->setValueKey($valueKey);

		if ($picklist['default_item']) {
			if ($valueKey === 'id') {
				$this->setDefault($picklist['default_item']);
			}
			//we need to find the correct default value to be selected
			elseif ($item = Arr::first($picklist['items'], function($item) use ($picklist) {
				return $item['id'] = $picklist['default_item'];
			}))
			{
				$this->setDefault($item[$valueKey]);
			}
		}

		return $this;
	}
}