<?php

namespace Foundry\System\Providers;

use Illuminate\Support\ServiceProvider;

class SystemServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
	    $this->app->register(RouteServiceProvider::class);
	    $this->app->register(EventServiceProvider::class);
	    $this->app->register(AuthServiceProvider::class);
	    //$this->app->register(BroadcastServiceProvider::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

}
