<?php

namespace Modules\ModuleRelease2\Providers\Concerns;

trait BindRepositories
{
    protected function bindRepositories(): void
    {
        $this->app->bind(\Modules\ModuleRelease2\Repositories\Interfaces\SettingRepositoryInterface::class, \Modules\ModuleRelease2\Repositories\SettingRepository::class);
        $this->app->bind(\Modules\ModuleRelease2\Repositories\Interfaces\MediaUsageRepositoryInterface::class, \Modules\ModuleRelease2\Repositories\MediaUsageRepository::class);
        $this->app->bind(\Modules\ModuleRelease2\Repositories\Interfaces\MediaRepositoryInterface::class, \Modules\ModuleRelease2\Repositories\MediaRepository::class);
        $this->app->bind(\Modules\ModuleRelease2\Repositories\Interfaces\ApiClientRepositoryInterface::class, \Modules\ModuleRelease2\Repositories\ApiClientRepository::class);
        $this->app->bind(\Modules\ModuleRelease2\Repositories\Interfaces\PermissionRepositoryInterface::class, \Modules\ModuleRelease2\Repositories\PermissionRepository::class);
        $this->app->bind(\Modules\ModuleRelease2\Repositories\Interfaces\RoleRepositoryInterface::class, \Modules\ModuleRelease2\Repositories\RoleRepository::class);
        $this->app->bind(\Modules\ModuleRelease2\Repositories\Interfaces\UserRepositoryInterface::class, \Modules\ModuleRelease2\Repositories\UserRepository::class);
        $this->app->bind(\Modules\ModuleRelease2\Repositories\Interfaces\LogActivityRepositoryInterface::class, \Modules\ModuleRelease2\Repositories\LogActivityRepository::class);
        
    }
}