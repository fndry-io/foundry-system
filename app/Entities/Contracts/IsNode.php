<?php

namespace Foundry\System\Entities\Contracts;

interface IsNode
{

	/**
	 * @return mixed
	 */
	public function getParent();
}