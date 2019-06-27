<?php

namespace Foundry\System\Repositories;

use Foundry\Core\Repositories\EntityRepository;

class AddressRepository extends EntityRepository {

	public function getAlias(): string {
		return 'a';
	}

}