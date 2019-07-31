<?php

namespace Foundry\System\Repositories;

use Foundry\Core\Repositories\EntityRepository;

class FileRepository extends EntityRepository {

	public function getAlias(): string {
		return 'file';
	}

}