<?php

namespace Foundry\System\Inputs\Types\Traits;

use Foundry\Core\Inputs\Types\Traits\HasTaggable;
use Foundry\System\Repositories\PickListRepository;
use Illuminate\Support\Arr;

/**
 * Trait PickListable
 *
 * A trait to help is using pick lists in ChoiceInputTypes
 *
 * @package Foundry\System\Inputs\Types\Traits
 */
trait PickListable {

	use HasTaggable;

	protected $picklist;

	/**
	 * @param string $identifier The pick list identifier to find the pick list options from
	 * @param string $valueKey The property of the pick list item to use as the value in the options
	 * @param string $labelKey The property of the pick list item to use as the label in the options
	 *
	 * @return $this
	 */
	protected function setPickList($identifier, $valueKey = 'id', $labelKey = 'label')
	{
		$this->picklist = PickListRepository::get()->getCachedSelectableList($identifier);

		$this->setOptions($this->picklist['items']);
		$this->setTextKey($labelKey);
		$this->setValueKey($valueKey);

		if ($this->picklist['default_item']) {
			if ($valueKey === 'id') {
				$this->setDefault($this->picklist['default_item']);
			}
			//we need to find the correct default value to be selected
			elseif ($item = Arr::first($this->picklist['items'], function($item) {
				return $item['id'] = $this->picklist['default_item'];
			}))
			{
				$this->setDefault($item[$valueKey]);
			}
		}

		return $this;
	}

	/**
	 * @param $identifier
	 * @param string $valueKey
	 * @param string $labelKey
	 *
	 * @return $this
	 */
	protected function setPickListWithTaggable($identifier, $valueKey = 'id', $labelKey = 'label') {
		$this
			->setPickList($identifier, $valueKey, $labelKey);

		$this
			->setTaggable(true)
			->setSearchable(true)
			->setParams(['_entity' => $this->picklist['id']])
			->setUrl(resourceUri('foundry.system.pick-lists.items.list'))
		;

		//todo add permission check to ensure the user has this right
		if ($this->picklist['is_tag']) {
			$this
				->setTaggableParam('label')
				->setTaggableUrl(resourceUri('foundry.system.pick-lists.items.add'))
			;
		}

		return $this;
	}
}