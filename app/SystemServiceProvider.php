<?php

namespace Foundry\System;

use Carbon\Carbon;
use Foundry\Core\Models\Contracts\HasAdminRights;
use Foundry\Core\Support\ServiceProvider;
use Foundry\System\Console\Commands\SyncCommand;
use Foundry\System\Providers\AuthServiceProvider;
use Foundry\System\Providers\EventServiceProvider;
use Foundry\System\Providers\RouteServiceProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;


class SystemServiceProvider extends ServiceProvider
{

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

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
	 * Boot the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->registerTranslations();
		$this->registerConfig();
		$this->registerViews();
		$this->registerCommands();
		$this->loadMigrationsFrom(base_path('foundry/system/database/migrations'));
		$this->registerGates();

		Validator::extend('username', function ($attribute, $value, $parameters, $validator) {
			return preg_match('/^[A-Za-z0-9_]+$/', $value);
		});
		Validator::extend('telephone', function ($attribute, $value, $parameters, $validator) {
			return preg_match('/^\+[0-9]{1,15}$/', $value);
		});
        Validator::extend('valid_date', function ($attribute, $value, $parameters, $validator) {
            if ($value instanceof Carbon) {
                return true;
            } elseif (is_string($value)) {
                try {
                    $date = Carbon::createFromFormat($parameters[0], $value);
                    return true;
                } catch (\Throwable $e) {
                    return false;
                }
            }

            return false;
        });
        Validator::extend('file_exists', function($attribute, $value, $parameters)
        {
            if (is_array($value)) {
                $value = Arr::get($value, 'id');
            }
            return DB::table('system_files')->where('id', $value)->exists();
        });
	}


	/**
	 * Register config.
	 *
	 * @return void
	 */
	protected function registerConfig()
	{
		$this->publishes([
			base_path('foundry/system/config/scope.php') => config_path('scope.php'),
		], 'scope');
	}

	/**
	 * Register views.
	 *
	 * @return void
	 */
	public function registerViews()
	{
		$viewPath = resource_path('views/foundry/system');

		$sourcePath = base_path('foundry/system/resources/views');

		$this->publishes([
			$sourcePath => $viewPath
		],'views');

		$this->loadViewsFrom(array_merge(array_map(function ($path) {
			return $path . '/foundry/system';
		}, Config::get('view.paths')), [$sourcePath]), 'foundry_system');
	}

	/**
	 * Register translations.
	 *
	 * @return void
	 */
	public function registerTranslations()
	{
		$langPath = resource_path('lang/plugins/foundry_system');

		if (is_dir($langPath)) {
			$this->loadTranslationsFrom($langPath, 'foundry_system');
		} else {
			$this->loadTranslationsFrom(base_path('foundry/system/resources/lang'), 'foundry_system');
		}
		$this->publishes([
			base_path('foundry/system/resources/lang') => $langPath,
		]);
	}

	/**
	 * Registers the commands for this service provider
	 *
	 * @return void
	 */
	public function registerCommands()
	{
		$this->commands([
//			UsersRegisterCommand::class,
//			ThemeLinkCommand::class,
//			SymLinkCommand::class
            SyncCommand::class
		]);
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return [];
	}

	public function registerGates()
	{
		Gate::before(function (Authenticatable $user, $ability) {
			if (($user instanceof HasAdminRights) && ($user->isSuperAdmin() || $user->isAdmin())) {
				return true;
			}
		});
	}

}
