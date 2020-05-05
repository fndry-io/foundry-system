<?php

namespace Foundry\System\Models;

class Permission extends \Spatie\Permission\Models\Permission {


    protected $table = 'system_permissions';

	/**
	 * Seed the database with a set of permissions
	 *
	 * @param $module
	 * @param $guard_name
	 * @param $groups
	 */
	static function seed($module, $guard_name, $groups ) {
		$perms = Permission::query()->where( 'module', $module )->where( 'guard_name', $guard_name )->get()->pluck( 'name', 'id' )->toArray();

		foreach ( $groups as $group => $permissions ) {
			foreach ( $permissions as $permission ) {
				$perm = Permission::query()->where( 'name', $permission )->where( 'guard_name', $guard_name )->first();
				if ( ! $perm ) {
					$perm = new Permission( [ 'name' => $permission, 'guard_name' => $guard_name ] );
				}
				$key = array_search( $permission, $perms );

				if ( $key !== false ) {
					unset( $perms[ $key ] );
				}
				$perm->module = $module;
				$perm->group  = $group;
				$perm->save();
			}
		}

		//remove the balance
		if (!empty($perms)) {
			Permission::query()->where( 'module', $module )->where( 'guard_name', $guard_name )->whereIn( 'id', array_keys($perms) )->forceDelete();
		}

	}

}
