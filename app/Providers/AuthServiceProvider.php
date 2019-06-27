<?php

namespace Foundry\System\Providers;

use Foundry\Core\Auth\TokenGuard;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
	    Auth::extend('token', function ($app, $guard, $config) {
		    return new TokenGuard(Auth::createUserProvider($config['provider']), $app->request, 'api_token', 'api_token', $config['hash']);
	    });
    }
}
