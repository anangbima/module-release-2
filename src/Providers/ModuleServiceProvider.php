<?php 

namespace Modules\ModuleRelease2\Providers;

use Illuminate\Cache\CacheManager;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Modules\ModuleRelease2\Providers\Concerns\BindRepositories;
use Modules\ModuleRelease2\Providers\Concerns\ConfigureNotifications;
use Modules\ModuleRelease2\Providers\Concerns\ConfigurePermissions;
use Modules\ModuleRelease2\Providers\Concerns\ConfigureSession;
use Modules\ModuleRelease2\Providers\Concerns\LoadAuthConfig;
use Modules\ModuleRelease2\Providers\Concerns\LoadDatabaseConfig;
use Modules\ModuleRelease2\Providers\Concerns\LoadModelConfigs;
use Modules\ModuleRelease2\Providers\Concerns\RegisterCommands;
use Modules\ModuleRelease2\Providers\Concerns\RegisterHelpers;
use Modules\ModuleRelease2\Providers\Concerns\RegisterMiddlewares;
use Modules\ModuleRelease2\Services\Shared\PermissionRegistrar;

class ModuleServiceProvider extends ServiceProvider
{
    use RegisterCommands, 
        LoadDatabaseConfig, 
        LoadAuthConfig, 
        ConfigurePermissions, 
        BindRepositories, 
        LoadModelConfigs, 
        RegisterMiddlewares, 
        RegisterHelpers, 
        ConfigureSession,
        ConfigureNotifications;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        // Register meiddlewares
        $this->registerMiddlewares();

        // Load migrations from the module
        // Only load if the application is in the 'test' environment.
        // This is useful for module-specific migrations during automated testing,
        // so that the migrations don't affect other environments (like production or local).
        if (app()->environment('test')) {
            $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        }

        // load views from the module
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'module-release-2');

        // register view components
        Blade::componentNamespace('Modules\\ModuleRelease2\\View\\Components', 'module-release-2');

        // load configure session
        $this->configureSession();

        // Configure notifications
        $this->configureNotifications();

        // Load permission and role spatie
        $this->configurePermissions();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // Merge configuration files from the module
        $this->mergeConfigFrom(__DIR__ . '/../../config/general.php', 'modulerelease2');
        
        // Register the route servie provider for this module
        $this->app->register(RouteServiceProvider::class);
        
        // Load the authentication configuration for this module
        $this->loadAuthConfig();
        
        // Register command for installing the module
        $this->registerModuleCommands();
        
        // load database only in test environmenr
        // if (app()->environment('test')) {
        //     $this->loadDatabaseConfig();
        // }

        $this->loadDatabaseConfig();
        
        // Binding Repository
        $this->bindRepositories();

        // load upload config
        $this->loadModelConfigs();

        // load helper
        $this->registerHelpers();

        $this->app->singleton(PermissionRegistrar::class, function ($app) {
            return new PermissionRegistrar(
                $app->make(CacheManager::class),
                $app->make(Dispatcher::class)
            );
        });
    }
}