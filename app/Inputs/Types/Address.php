<?php

namespace Foundry\System\Inputs\Types;

use Foundry\Core\Entities\Entity;
use Foundry\Core\Inputs\Contracts\Field;
use Foundry\Core\Inputs\Types\AddButtonType;
use Foundry\Core\Inputs\Types\Contracts\Inputable;
use Foundry\Core\Inputs\Types\EditButtonType;
use Foundry\Core\Inputs\Types\ReferenceInputType;
use Illuminate\Support\Facades\Auth;

class Address extends ReferenceInputType implements Field {

	/**
	 * @param Entity|null $entity
	 *
	 * @return Address|ReferenceInputType|Inputable
	 */
	static function input( Entity &$entity = null ) : Inputable {
		$input = (new static(
			'address',
			__('Address'),
			false,
			'address',
			null
		))
			->setRules([
				'exists:Foundry\System\Entities\Address,id',
			])
			->setSortable(false)
			->setOptions([])
			->setTextKey(['street', 'city', 'country', 'code'], ', ')
			->setValueKey('id')
		;
		$input->setButtons((new AddButtonType('add', __('New')))->setAction(route('foundry.system.addresses.add', [], false)));
		$input->setButtons((new EditButtonType('edit', __('Edit')))->setAction(routeUri('foundry.system.addresses.edit')));
		return $input;
	}

}