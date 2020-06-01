<?php

namespace Foundry\System\Inputs\User\Types;

use Foundry\Core\Inputs\Contracts\Field;
use Foundry\Core\Inputs\Contracts\FieldOptions;
use Foundry\Core\Inputs\Types\ChoiceInputType;
use Foundry\Core\Inputs\Types\Contracts\Inputable;
use Foundry\System\Models\Role;

class Roles extends ChoiceInputType implements Field, FieldOptions {

    protected $cast = 'int';

	/**
	 * @return $this
	 */
	static function input(): Inputable
    {
		return ( new static(
			'roles',
			__( 'Roles' ),
			false,
            static::options(),
			false,
			true
		) )
            ->setTextKey('name')
            ->setValueKey('id')
			->setHelp(__('These are system level roles and will only be applied if the scope of the user is system.'))
			->setSortable( false );
	}

	static function options(): array {
		return Role::query()->select( [ 'id', 'name' ] )->orderBy( 'name', 'ASC' )->get()->toArray();
	}

}
