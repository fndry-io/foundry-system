<?php

namespace Foundry\System\Inputs\Types\Traits;

use Foundry\System\Entities\PickList;
use Illuminate\Support\Arr;
use LaravelDoctrine\ORM\Facades\EntityManager;

/**
 * Trait PickListable
 *
 * A trait to help is using pick lists in ChoiceInputTypes
 *
 * @package Foundry\System\Inputs\Types\Traits
 */
trait PickListable {

	/**
	 * @param string $identifier The pick list identifier to find the pick list options from
	 * @param string $valueKey The property of the pick list item to use as the value in the options
	 * @param string $labelKey The property of the pick list item to use as the label in the options
	 *
	 * @return $this
	 */
	protected function setPickList($identifier, $valueKey = 'id', $labelKey = 'label')
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