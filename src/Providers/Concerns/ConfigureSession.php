<?php

namespace Modules\DesaModuleTemplate\Providers\Concerns;

use Illuminate\Session\SessionManager;
use Illuminate\Support\Facades\Session;
use Modules\DesaModuleTemplate\Session\ModuleSessionHandler;

trait ConfigureSession
{
    protected function configureSession()
    {
        $this->app->resolving(SessionManager::class, function ($sessionManager) {
            config([
                'session.driver'     => env('DESA_MODULE_TEMPLATE_SESSION_DRIVER', 'database'),
                'session.connection' => env('DESA_MODULE_TEMPLATE_SESSION_CONNECTION', 'desa_module_template'),
                'session.table'      => env('DESA_MODULE_TEMPLATE_SESSION_TABLE', 'desa_module_template_sessions'),
                'session.cookie'     => env('DESA_MODULE_TEMPLATE_SESSION_COOKIE', 'desa_module_template_session'),
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
