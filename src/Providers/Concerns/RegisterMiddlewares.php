<?php

namespace Modules\ModuleRelease2\Providers\Concerns;

use Modules\ModuleRelease2\Http\Middleware\CheckUserStatus;
use Modules\ModuleRelease2\Http\Middleware\EnsureEmailIsVerified;
use Modules\ModuleRelease2\Http\Middleware\EnsureRole;
use Modules\ModuleRelease2\Http\Middleware\RedirectIfAuthenticated;
use Modules\ModuleRelease2\Http\Middleware\VerifyApiClient;

trait RegisterMiddlewares
{
    protected function registerMiddlewares(): void
    {
        $router = app('router');

        $router->aliasMiddleware('module_release_2.role', EnsureRole::class);
        $router->aliasMiddleware('module_release_2.guest', RedirectIfAuthenticated::class);
        $router->aliasMiddleware('module_release_2.verified', EnsureEmailIsVerified::class);
        $router->aliasMiddleware('module_release_2.status', CheckUserStatus::class);
        $router->aliasMiddleware('module_release_2.verify_api_client', VerifyApiClient::class);
    }
}
