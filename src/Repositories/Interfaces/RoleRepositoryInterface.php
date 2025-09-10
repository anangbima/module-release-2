<?php

namespace Modules\ModuleRelease2\Repositories\Interfaces;

interface RoleRepositoryInterface
{
    public function all(...$relations);
    public function find($id, ...$relations);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
}