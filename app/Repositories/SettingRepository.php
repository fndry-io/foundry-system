<?php

namespace Foundry\Core\Config;

use ArrayAccess;
use Illuminate\Support\Arr;
use Foundry\Core\Contracts\Repository;

class SettingRepository implements Repository, ArrayAccess {

	/**
	 * All of the settings items.
	 *
	 * @var array
	 */
	protected $items = [];

	/**
	 * Create a new settings repository.
	 *
	 * @param  array $items
	 *
	 * @return void
	 */
	public function __construct( array $items = [] ) {
		$this->items = $items;
	}

	/**
	 * Determine if the given setting value exists.
	 *
	 * @param  string $key
	 *
	 * @return bool
	 */
	public function has( $key ) {
		return Arr::has( $this->items, $key );
	}

	/**
	 * Get the specified setting value.
	 *
	 * @param  array|string $key
	 * @param  mixed $default
	 *
	 * @return mixed
	 */
	public function get( $key, $default = null ) {
		if ( is_array( $key ) ) {
			return $this->getMany( $key );
		}

		return Arr::get( $this->items, $key, $default );
	}

	/**
	 * Get many setting values.
	 *
	 * @param  array $keys
	 *
	 * @return array
	 */
	public function getMany( $keys ) {
		$settings = [];

		foreach ( $keys as $key => $default ) {
			if ( is_numeric( $key ) ) {
				[ $key, $default ] = [ $default, null ];
			}

			$settings[ $key ] = Arr::get( $this->items, $key, $default );
		}

		return $settings;
	}

	/**
	 * Set a given setting value.
	 *
	 * @param  array|string $key
	 * @param  mixed $value
	 *
	 * @return void
	 */
	public function set( $key, $value = null ) {
		$keys = is_array( $key ) ? $key : [ $key => $value ];

		foreach ( $keys as $key => $value ) {
			//Todo persist to database
			Arr::set( $this->items, $key, $value );
		}
	}

	/**
	 * Get all of the setting items for the application.
	 *
	 * @return array
	 */
	public function all() {
		return $this->items;
	}

	/**
	 * Determine if the given setting option exists.
	 *
	 * @param  string $key
	 *
	 * @return bool
	 */
	public function offsetExists( $key ) {
		return $this->has( $key );
	}

	/**
	 * Get a setting option.
	 *
	 * @param  string $key
	 *
	 * @return mixed
	 */
	public function offsetGet( $key ) {
		return $this->get( $key );
	}

	/**
	 * Set a setting option.
	 *
	 * @param  string $key
	 * @param  mixed $value
	 *
	 * @return void
	 */
	public function offsetSet( $key, $value ) {
		$this->set( $key, $value );

		//Todo persist to database
	}

	/**
	 * Unset a setting option.
	 *
	 * @param  string $key
	 *
	 * @return void
	 */
	public function offsetUnset( $key ) {
		$this->set( $key, null );

		//Todo persist to database
	}

	/**
	 * Get database table name for settings
	 *
	 * @return string
	 */
	static function getTable(): string {

		$s = config( 'foundry.settings' );

		$table = $s && is_array( $s ) && isset( $s['table'] ) ? $s['table'] : 'settings';

		return $table;
	}
}
