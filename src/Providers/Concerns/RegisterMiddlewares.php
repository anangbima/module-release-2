<?php

namespace Modules\DesaModuleTemplate\Providers\Concerns;

use Modules\DesaModuleTemplate\Http\Middleware\CheckUserStatus;
use Modules\DesaModuleTemplate\Http\Middleware\EnsureEmailIsVerified;
use Modules\DesaModuleTemplate\Http\Middleware\EnsureRole;
use Modules\DesaModuleTemplate\Http\Middleware\RedirectIfAuthenticated;
use Modules\DesaModuleTemplate\Http\Middleware\VerifyApiClient;

trait RegisterMiddlewares
{
    protected function registerMiddlewares(): void
    {
        $router = app('router');

        $router->aliasMiddleware('desa_module_template.role', EnsureRole::class);
        $router->aliasMiddleware('desa_module_template.guest', RedirectIfAuthenticated::class);
        $router->aliasMiddleware('desa_module_template.verified', EnsureEmailIsVerified::class);
        $router->aliasMiddleware('desa_module_template.status', CheckUserStatus::class);
        $router->aliasMiddleware('desa_module_template.verify_api_client', VerifyApiClient::class);
    }
}
