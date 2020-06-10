<?php

namespace Foundry\System\Inputs\Types;


use Foundry\Core\Inputs\Contracts\Field;
use Foundry\Core\Inputs\Types\AddButtonType;
use Foundry\Core\Inputs\Types\Contracts\Inputable;
use Foundry\Core\Inputs\Types\ReferenceInputType;
use Illuminate\Support\Facades\Auth;

class User extends ReferenceInputType implements Field {

	/**
	 *
	 * @return User|ReferenceInputType|Inputable
	 */
	static function input( ) : Inputable {
		$input = (new static(
			'user',
			__('User'),
			false,
			null,
			route('system.users.list', [], false)
		))
			->setRules([
				'exists:system_users,id',
			])
			->setSortable(false)
			->setOptions([])
			->setTextKey(['display_name', 'username'], ', ')
			->setValueKey('id')
		;

		if (Auth::user() && Auth::user()->can('system.users.manage')) {
			$input->setButtons((new AddButtonType('add', __('New')))->setAction(route('system.users.add', [], false)));
		}
		return $input;
	}

}
