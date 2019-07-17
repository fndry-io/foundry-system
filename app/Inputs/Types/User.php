<?php

namespace Foundry\System\Inputs\Types;

use Foundry\Core\Entities\Entity;
use Foundry\Core\Inputs\Contracts\Field;
use Foundry\Core\Inputs\Types\AddButtonType;
use Foundry\Core\Inputs\Types\Contracts\Inputable;
use Foundry\Core\Inputs\Types\ReferenceInputType;
use Illuminate\Support\Facades\Auth;

class User extends ReferenceInputType implements Field {

	/**
	 * @param Entity|null $entity
	 *
	 * @return User|ReferenceInputType|Inputable
	 */
	static function input( Entity &$entity = null ) : Inputable {
		$input = (new static(
			'user',
			__('User'),
			false,
			'user',
			route('foundry.system.users.list', [], false)
		))
			->setRules([
				'exists:Foundry\System\Entities\User,id',
			])
			->setSortable(false)
			->setOptions([])
			->setTextKey(['display_name', 'username'], ', ')
			->setValueKey('id')
		;

		if (Auth::user() && Auth::user()->can('manage users')) {
			$input->setButtons((new AddButtonType('add', __('New')))->setAction(route('foundry.system.users.add', [], false)));
		}
		return $input;
	}

}