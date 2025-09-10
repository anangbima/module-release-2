<?php 

namespace Modules\DesaModuleTemplate\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Modules\DesaModuleTemplate\Http\Middleware\EnsureAuthenticated;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Define route model bindings, pattern filters, etc.
     * 
     * @return void
     */
    public function boot()
    {   
        // load configure limiting
        $this->configureRateLimiting();

        Route::middlewareGroup('desa_module_template-auth', [
            EnsureAuthenticated::class . ':desa_module_template_web',
        ]);

        // web routes with custom module guard and session config override
        $this->loadRoutesFromDirectory(
            directory: __DIR__ . '/../../routes/web',
            middleware: ['web'],
            prefix: 'desa-module-template',
            name: 'desa-module-template'
        );

        // api routes with custom module guard
        $this->loadRoutesFromDirectory(
            directory: __DIR__ . '/../../routes/api',
            middleware: ['api'],
            prefix: 'desa-module-template/api',
            name: 'desa-module-template.api'
        );

        // external api routes with custom module guard
        $this->loadRoutesFromDirectory(
            directory: __DIR__ . '/../../routes/external-api',
            middleware: ['api', 'desa_module_template.verify_api_client'],
            prefix: 'desa-module-template/external-api',
            name: 'desa-module-template.external-api'
        );

        // channel routes
        $this->loadChannelRoutes(
            directory: __DIR__ . '/../../routes/channels',
            middleware: ['web', 'auth:desa_module_template_web'],
            prefix: 'desa-module-template'
        );
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('api-client', function (Request $request) {
            $clientId = optional($request->api_client)->id;

            return Limit::perMinute(60)->by($clientId ?? $request->ip());
        });
    }

    /**
     * Automatically load all route files in the given directory
     * and group them under the given middleware and prefix (optional).
     *
     * @param  string  $directory  Absolute path to the route directory
     * @param  array   $middleware Middleware to apply (e.g., ['web'], ['api'])
     * @param  string  $prefix     Optional prefix for the route group (default: '')
     * @return void
     */
    protected function loadRoutesFromDirectory(string $directory, array $middleware = [], string $prefix = '', string $name = '')
    {
        $routeFiles = glob($directory . '/*.php');

        foreach ($routeFiles as $routeFile) {
            Route::middleware($middleware)
                ->prefix($prefix)
                ->name($name.'.')
                ->group($routeFile);
        }
    }

    /**
     * Load channel routes from the specified directory.
     *
     * @param  string  $directory  Absolute path to the channel routes directory
     * @param  array   $middleware Middleware to apply (e.g., ['auth'])
     * @param  string  $prefix     Optional prefix for the channel routes (default: '')
     * @return void
     */
    protected function loadChannelRoutes(string $directory, array $middleware = [], string $prefix = '')
    {
        foreach (glob($directory . '/*.php') as $channelFile) {
            require $channelFile;
        }

        Broadcast::routes([
            'middleware' => $middleware,
            'prefix' => $prefix,
        ]);
    }
}