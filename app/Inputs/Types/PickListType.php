<?php

namespace Modules\Agm\Contacts\Inputs\Company\Types;

use Foundry\Core\Inputs\Contracts\Field;

use Foundry\Core\Inputs\Types\ChoiceInputType;
use Foundry\Core\Inputs\Types\Contracts\Inputable;
use Foundry\Core\Inputs\Types\Traits\HasParams;
use Foundry\Core\Inputs\Types\Traits\HasQueryOptions;
use Foundry\System\Inputs\Types\Traits\PickListable;

class PickListType extends ChoiceInputType implements Field {

	use PickListable;
	use HasParams;
	use HasQueryOptions;

	/**
	*
	*
	* @return Inputable|Profile
	*/
	static function input( ): Inputable {
		return ( new static(
			'tags',
			__('Tags'),
			false,
			[],
			false,
			true
		) )
			->setValueKey('id')
			->setTextKey('label')
			->setHelp(__('A series of tags to associate with the company'))
			->setPickList('agm_contacts_tags')
			;
	}

	public function __construct(
		string $name,
		string $label = null,
		bool $required = true,
		array $options = [],
		bool $expanded = false,
		bool $multiple = false,
		$value = null,
		string $position = 'full',
		string $rules = null,
		string $id = null,
		string $placeholder = null
	) {
		parent::__construct($name, $label, $required, $options, $expanded, $multiple, $value, $position, $rules, $id,
			$placeholder);

		$this->setType('pick-list');
	}


}