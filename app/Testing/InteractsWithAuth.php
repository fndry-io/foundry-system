<?php

namespace Foundry\System\Testing;

use Illuminate\Contracts\Auth\Authenticatable;

trait InteractsWithAuth
{
    protected $auth;

    /**
     * Logs in as user 'admin'
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function login()
    {
        $this->loginAs('admin', 'system');
    }

    /**
     * @param $value
     * @param null $guard
     * @param string $key
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function loginAs($value, $guard = null, $key = 'username')
    {
        $user = null;
        if ($value instanceof Authenticatable) {
            $user = $value;
        } elseif (is_string($value)) {
            $user = $this->getUser($value, $guard, $key);
        }
        if ($user) {
            $this->be($user, $guard);
            $this->auth = $user;
        }
    }

    /**
     * @param $value
     * @param string $key
     * @param null $guard
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function getUser($value, $guard = null, $key = 'username')
    {
        $config = $this->app->make('config');
        $provider = $config->get('auth.guards.' . $guard . '.provider');
        /** @var Authenticatable $model */
        $model = $config->get('auth.providers.' . $provider . '.model');
        return $model::query()->where($key, $value)->first();
    }

    /**
     * @param array $manager_perms Manager Permissions
     * @param array $dummy_perms Dummy user Permissions
     */
    public function setPermissions($manager_perms, $dummy_perms)
    {
        //assign manager permissions
        $manager_role = \Foundry\System\Models\Role::findOrCreate([
            'name' => 'Manager',
            'guard_name' => 'system'
        ]);
        $manager_role->givePermissionTo(
            ...$manager_perms
        );

        //assign permissions to dummy's for managing their own statistics
        $dummy_role = \Foundry\System\Models\Role::findOrCreate([
            'name' => 'Dummy',
            'guard_name' => 'system'
        ]);
        $dummy_role->givePermissionTo(
            ...$dummy_perms
        );

    }
}
