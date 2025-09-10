<?php

namespace Modules\DesaModuleTemplate\Repositories;

use Modules\DesaModuleTemplate\Models\Setting;
use Modules\DesaModuleTemplate\Repositories\Interfaces\SettingRepositoryInterface;

class SettingRepository implements SettingRepositoryInterface
{
    protected $model;

    public function __construct()
    {
        $this->model = new Setting();
    }

    /**
     * Get all settings.
     */
    public function all()
    {
        return $this->model->orderBy('created_at', 'desc')->get();
    }

    /**
     * Get a setting by key.
     */
    public function get(string $key, mixed $default = null): mixed
    {
        $setting = $this->model->where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Set a setting by key.
     */
    public function set(string $key, mixed $value, string $type = 'string'): void
    {
        $this->model->updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'type' => $type]
        );
    }

    /**
     * Find settings by group.
     */
    public function findByGroup(string $group): array
    {
        return $this->model->where('group', $group)->get();
    }
}