<?php

namespace Foundry\System\Inputs\Address;

use Foundry\Core\Inputs\Inputs;
use Foundry\Core\Support\InputTypeCollection;
use Foundry\System\Inputs\Address\Types\City;
use Foundry\System\Inputs\Address\Types\Code;
use Foundry\System\Inputs\Address\Types\Country;
use Foundry\System\Inputs\Address\Types\Region;
use Foundry\System\Inputs\Address\Types\Street;
use Foundry\System\Inputs\Address\Types\Type;

/**
 * Class AddressInput
 *
 * @package Foundry\System\Inputs
 *
 */
class AddressInput extends Inputs {

	protected $fillable = [
		'type',
		'street',
		'city',
		'region',
		'country',
		'code',
	];

	public function types() : InputTypeCollection
	{
		return InputTypeCollection::fromTypes([
			Type::input(),
			Street::input(),
			City::input(),
			Region::input(),
			Country::input(),
			Code::input()
		]);
	}

}