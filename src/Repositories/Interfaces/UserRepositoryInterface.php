<?php

namespace Modules\DesaModuleTemplate\Repositories\Interfaces;

interface UserRepositoryInterface
{
    public function all(...$relations);
    public function find($id, ...$relations);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function checkByEmail(string $email): bool;
    public function countByStatus(string $status): int;
}