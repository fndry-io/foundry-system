<?php

namespace Foundry\System\Repositories;

use Foundry\Core\Repositories\EntityRepository;

class RoleRepository extends EntityRepository {

	public function getAlias(): string {
		return 'r';
	}

}