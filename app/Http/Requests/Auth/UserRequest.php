<?php

namespace Foundry\System\Http\Requests\Auth;

use Foundry\Core\Inputs\Types\FormType;
use Foundry\Core\Requests\Contracts\EntityRequestInterface;
use Foundry\Core\Requests\FormRequest;
use Foundry\Core\Requests\Traits\HasEntity;
use Foundry\System\Models\User;

abstract class UserRequest extends FormRequest implements EntityRequestInterface
{
	use HasEntity;

	public function form(): FormType {
		$this->setEntity($this->user());
		return parent::form();
	}

	/**
	 * @param mixed $id
	 *
	 * @return null|User|object
	 */
	public function findEntity($id)
	{
		return $this->user();
	}


}
