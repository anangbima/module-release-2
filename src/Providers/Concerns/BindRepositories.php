<?php

namespace Modules\DesaModuleTemplate\Providers\Concerns;

trait BindRepositories
{
    protected function bindRepositories(): void
    {
        $this->app->bind(\Modules\DesaModuleTemplate\Repositories\Interfaces\SettingRepositoryInterface::class, \Modules\DesaModuleTemplate\Repositories\SettingRepository::class);
        $this->app->bind(\Modules\DesaModuleTemplate\Repositories\Interfaces\MediaUsageRepositoryInterface::class, \Modules\DesaModuleTemplate\Repositories\MediaUsageRepository::class);
        $this->app->bind(\Modules\DesaModuleTemplate\Repositories\Interfaces\MediaRepositoryInterface::class, \Modules\DesaModuleTemplate\Repositories\MediaRepository::class);
        $this->app->bind(\Modules\DesaModuleTemplate\Repositories\Interfaces\ApiClientRepositoryInterface::class, \Modules\DesaModuleTemplate\Repositories\ApiClientRepository::class);
        $this->app->bind(\Modules\DesaModuleTemplate\Repositories\Interfaces\PermissionRepositoryInterface::class, \Modules\DesaModuleTemplate\Repositories\PermissionRepository::class);
        $this->app->bind(\Modules\DesaModuleTemplate\Repositories\Interfaces\RoleRepositoryInterface::class, \Modules\DesaModuleTemplate\Repositories\RoleRepository::class);
        $this->app->bind(\Modules\DesaModuleTemplate\Repositories\Interfaces\UserRepositoryInterface::class, \Modules\DesaModuleTemplate\Repositories\UserRepository::class);
        $this->app->bind(\Modules\DesaModuleTemplate\Repositories\Interfaces\LogActivityRepositoryInterface::class, \Modules\DesaModuleTemplate\Repositories\LogActivityRepository::class);
        
    }
}