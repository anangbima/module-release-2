<?php

namespace Modules\DesaModuleTemplate\Repositories\Interfaces;

interface SettingRepositoryInterface
{
    public function all();
    public function get(string $key, mixed $default = null): mixed;
    public function set(string $key, mixed $value, string $type = 'string'): void;
    public function findByGroup(string $group): array;
}