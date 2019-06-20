<?php

namespace Foundry\System\Providers;

use Foundry\Core\Requests\FormRequestHandler;
use Foundry\System\Console\Commands\UsersRegisterCommand;
use Foundry\System\Entities\User;
use Foundry\System\Repositories\UserRepository;
use Foundry\System\Services\UserService;
use Illuminate\Foundation\Console\SymLinkCommand;
use Illuminate\Foundation\Console\ThemeLinkCommand;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

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
	    $this->registerRepositories();
	    $this->registerServices();
	    $this->registerFormRequests();
    }

    public function registerRepositories()
    {
	    $this->app->bind(UserRepository::class, function($app) {
		    return new UserRepository(
			    $app['em'],
			    $app['em']->getClassMetaData(User::class)
		    );
	    });
    }

	public function registerServices()
	{
		$this->app->bind(UserService::class, function($app) {
			return new UserService(
				resolve(UserRepository::class)
			);
		});

		$this->app->singleton( 'Foundry\Core\Contracts\FormRequestHandler', function () {
			return new FormRequestHandler();
		} );
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
		$this->registerFactories();
		$this->registerCommands();
		$this->loadMigrationsFrom(base_path('foundry/system/database/migrations'));
		$this->registerGates();

	}


	/**
	 * Register config.
	 *
	 * @return void
	 */
	protected function registerConfig()
	{
//		$this->publishes([
//			base_path('foundry/system/config/config.php') => config_path('foundry_system.php'),
//		], 'config');
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
	 * Register an additional directory of factories.
	 *
	 * @return void
	 */
	public function registerFactories()
	{

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
//		Gate::before(function ($user, $ability) {
//			/**
//			 * @var User $user
//			 */
//			if ($user->isAdmin()) {
//				return true;
//			}
//		});
	}

	public function registerFormRequests()
	{
		app(\Foundry\Core\Contracts\FormRequestHandler::class)->register([
			'Foundry\System\Http\Requests\Auth\LoginRequest',
			'Foundry\System\Http\Requests\Auth\LogoutRequest',
			'Foundry\System\Http\Requests\Auth\ForgotPasswordRequest',
			'Foundry\System\Http\Requests\Auth\ResetPasswordRequest',
			'Foundry\System\Http\Requests\Users\BrowseUsersRequest',
			'Foundry\System\Http\Requests\Users\RegisterUserRequest',

		]);

	}

}
