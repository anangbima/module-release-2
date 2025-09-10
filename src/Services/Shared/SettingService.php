<?php

namespace Modules\ModuleRelease2\Services\Shared;

use Modules\ModuleRelease2\Repositories\Interfaces\SettingRepositoryInterface;

class SettingService
{
    public function __construct(
        protected SettingRepositoryInterface $settingRepository,
        protected LogActivityService $logActivityService,
    ){ }

    /**
     * Get all settings.
     */
    public function getAllSetting()
    {
        return $this->settingRepository->all();
    }

    /**
     * Get a setting by key.
     */
    public function getSettingByKey(string $key, mixed $default = null)
    {
        return $this->settingRepository->get($key, $default);
    }

    /**
     * Get settings by group.
     */
    public function getSettingsByGroup(string $group): array
    {
        return $this->settingRepository->findByGroup($group);
    }

}