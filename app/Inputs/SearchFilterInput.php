<?php

namespace Foundry\System\Inputs;

use Foundry\Core\Inputs\Inputs;
use Foundry\Core\Inputs\Types\TextInputType;
use Foundry\Core\Support\InputTypeCollection;

/**
 * Class SearchFilterInput
 *
 * @package Foundry\System\Inputs
 *
 * @property $search
 */
class SearchFilterInput extends Inputs {

	protected $fillable = [
		'search'
	];

	public function types() : InputTypeCollection
	{
		return InputTypeCollection::fromTypes([
			(new TextInputType('search', 'Search', false))
		]);
	}

}