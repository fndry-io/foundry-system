<?php

namespace Foundry\System\Inputs\Types;

use Foundry\Core\Inputs\Contracts\Field;
use Foundry\Core\Inputs\Contracts\FieldOptions;
use Foundry\Core\Inputs\Types\AddButtonType;
use Foundry\Core\Inputs\Types\ChoiceInputType;
use Foundry\Core\Inputs\Types\Contracts\Inputable;
use Foundry\Core\Inputs\Types\ReferenceInputType;
use Foundry\System\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;

class UserSelect extends ChoiceInputType implements Field, FieldOptions
{

	/**
	 *
	 * @return User|ReferenceInputType|Inputable
	 */
	static function input( ) : Inputable {
		$input = (new static(
			'user',
			__('User'),
			false,
			static::options()
		))
			->setRules([
				'exists:users,id',
			])
			->setSortable(false)
			->setTextKey('display_name')
            ->setValueKey('id')
		;

        if (Auth::user() && Auth::user()->can('add users')) {
            $input->setButtons((new AddButtonType('add', __('New')))->setAction(route('foundry.system.users.add', [], false)));
        }

        return $input;
	}


    /**
     * The input options
     *
     * @param \Closure $closure A query builder to modify the query if needed
     * @param mixed $value
     *
     * @return array
     */
    static function options(\Closure $closure = null, $value = null): array
    {
        return UserRepository::repository()->getLabelList(null, null)->toArray();
    }
}
