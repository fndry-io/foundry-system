<?php

namespace Foundry\System\Repositories;

use Foundry\Core\Models\Model;
use Foundry\Core\Repositories\ModelRepository;
use Foundry\System\Models\File;

class FileRepository extends ModelRepository {

	/**
	 * @return string|Model
	 */
	public function getClassName()
	{
		return File::class;
	}
}