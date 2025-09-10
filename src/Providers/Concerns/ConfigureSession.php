<?php

namespace Modules\ModuleRelease2\Providers\Concerns;

use Illuminate\Session\SessionManager;
use Illuminate\Support\Facades\Session;
use Modules\ModuleRelease2\Session\ModuleSessionHandler;

trait ConfigureSession
{
    protected function configureSession()
    {
        $this->app->resolving(SessionManager::class, function ($sessionManager) {
            config([
                'session.driver'     => env('MODULE_RELEASE_2_SESSION_DRIVER', 'database'),
                'session.connection' => env('MODULE_RELEASE_2_SESSION_CONNECTION', 'module_release_2'),
                'session.table'      => env('MODULE_RELEASE_2_SESSION_TABLE', 'module_release_2_sessions'),
                'session.cookie'     => env('MODULE_RELEASE_2_SESSION_COOKIE', 'module_release_2_session'),
            ]);
        });
        
        // Register driver session custom
        Session::extend('module_database', function ($app) {
            $connection = $app['db']->connection(config('session.connection'));
            $table      = config('session.table');
            $lifetime   = config('session.lifetime');

            return new ModuleSessionHandler($connection, $table, $lifetime, $app);
        });
    }
}
