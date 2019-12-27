<?php

namespace Foundry\System\Entities\Traits;

/**
 * Blameable Trait
 *
 */
trait Blameable
{

	/**
	 * @var string
	 */
	private $created_by;

	/**
	 * @var string
	 */
	private $updated_by;

	/**
	 * Sets createdBy.
	 *
	 * @param  string $created_by
	 * @return $this
	 */
	public function setCreatedBy($created_by)
	{
		$this->created_by = $created_by;

		return $this;
	}

	/**
	 * Returns created_by.
	 *
	 * @return string
	 */
	public function getCreatedBy()
	{
		return $this->created_by;
	}

	/**
	 * Sets $updated_by.
	 *
	 * @param  string $updated_by
	 * @return $this
	 */
	public function setUpdatedBy($updated_by)
	{
		$this->updated_by = $updated_by;

		return $this;
	}

	/**
	 * Returns updatedBy.
	 *
	 * @return string|null
	 */
	public function getUpdatedBy()
	{
		return $this->updated_by;
	}
}
