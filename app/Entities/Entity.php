<?php

namespace Foundry\System\Entities;

use Foundry\Core\Entities\Traits\Fillable;
use Foundry\Core\Entities\Traits\Visible;
use Illuminate\Support\Str;

/**
 * Class Entity
 *
 * The base Entity class for extending
 *
 * @package Foundry\System\Entities
 */
abstract class Entity {

	use Fillable;
	use Visible;

	/**
	 * Entity constructor.
	 *
	 * @param array $properties
	 */
	public function __construct(array $properties = []) {
		$this->fill($properties);
	}

	/**
	 * Converts the entity to an array hiding any fields set in hidden
	 *
	 * @return array
	 */
	public function toArray() {
		$data = [];
		$hidden = (isset($this->hidden) && !empty($this->hidden)) ? $this->hidden : [];
		foreach ($this as $key => $value) {
			if (!in_array($key, $hidden)) {
				$data[$key] = $value;
			}
		}
		return $data;
	}

	/**
	 * Extract specific fields
	 *
	 * @param array $fields
	 *
	 * @return array
	 */
	public function only(array $fields)
	{
		$data = [];
		foreach ($fields as $key) {
			if (isset($this->$key)) {
				$data[$key] = $this->$key;
			}
		}
		return $data;
	}

	/**
	 * Gets a property from the entity
	 *
	 * This will call any getPropertyName method if it exists
	 *
	 * @param $name
	 *
	 * @return mixed
	 */
	public function __get( $name ) {
		if (method_exists($this, 'get' . Str::ucfirst(Str::camel($name)))) {
			return call_user_func([$this, 'get' . Str::ucfirst(Str::camel($name))]);
		} else {
			return $this->$name;
		}
	}

	/**
	 * Sets the property of an entity
	 *
	 * This will call any setPropertyName method if it exists
	 *
	 * @param $name
	 * @param $value
	 */
	public function __set( $name, $value ) {
		if (method_exists($this, 'set' . Str::ucfirst(Str::camel($name)))) {
			call_user_func([$this, 'set' . Str::ucfirst(Str::camel($name))], $value);
		} else {
			$this->$name = $value;
		}
	}

}