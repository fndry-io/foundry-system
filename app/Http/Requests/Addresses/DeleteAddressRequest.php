<?php

namespace Foundry\System\Http\Requests\Addresses;

use Foundry\Core\Requests\Contracts\EntityRequestInterface;
use Foundry\Core\Requests\Response;
use Foundry\System\Services\AddressService;

class DeleteAddressRequest  extends AddressRequest implements EntityRequestInterface
{

	public static function name(): String {
		return 'foundry.system.addresses.delete';
	}

	public function authorize()
	{
		return !!($this->user());
	}

	/**
	 * Handle the request
	 *
	 * @return Response
	 */
	public function handle() : Response
	{
		return AddressService::service()->delete($this->getEntity());
	}

}
