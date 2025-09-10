<?php

namespace Modules\DesaModuleTemplate\Repositories\Interfaces;

interface LogActivityRepositoryInterface
{
    public function all(...$relations);
    public function find(string $id, ...$relations);
    public function create(array $data);
    public function allByRole(string $role);
    public function allByUser(string $userId);
    public function latest(int $limit = 10);
}