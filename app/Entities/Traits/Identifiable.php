<?php

namespace Foundry\System\Entities\Traits;

trait Identifiable {

	/**
	 * @var mixed
	 */
	protected $id;

	/**
	 * @return mixed
	 */
	public function getId() {
		return $this->id;
	}

}