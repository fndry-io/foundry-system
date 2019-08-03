<?php

namespace Foundry\System\Inputs\Types\Traits;

use Foundry\Core\Inputs\SimpleInputs;
use Foundry\Core\Requests\Traits\HasInput;
use Foundry\Core\Support\InputTypeCollection;
use Foundry\System\Inputs\Types\ReferenceId;
use Foundry\System\Inputs\Types\ReferenceType;

trait ReferenceInputable {

	use HasInput;

	/**
	 * Make the input class for the request
	 *
	 * @param $inputs
	 *
	 * @return mixed
	 */
	public function makeInput( $inputs ) {
		return (new SimpleInputs($inputs, InputTypeCollection::make([
			ReferenceId::input(),
			ReferenceType::input()
		])));
	}

	/**
	 * Does the request have references
	 *
	 * @return bool
	 */
	public function hasReference()
	{
		return ($this->exists('reference_type') && $this->exists('reference_id'));
	}

	/**
	 * Get the reference type from the request inputs
	 *
	 * @return mixed
	 */
	public function getReferenceType()
	{
		return $this->input('reference_type');
	}

	/**
	 * Get the reference id from the request inputs
	 *
	 * @return mixed
	 */
	public function getReferenceId()
	{
		return $this->input('reference_id');
	}


}