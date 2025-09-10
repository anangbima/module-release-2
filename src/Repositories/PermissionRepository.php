<?php

namespace Modules\ModuleRelease2\Repositories;

use Modules\ModuleRelease2\Models\Permission;
use Modules\ModuleRelease2\Repositories\Interfaces\PermissionRepositoryInterface;
use Modules\ModuleRelease2\Traits\HasRelationable;

class PermissionRepository implements PermissionRepositoryInterface
{
    use HasRelationable;

    protected $model;

    public function __construct()
    {
        $this->model = new Permission();
    }

    /**
     * Get all permissions.
     */
    public function all(...$relations)
    {
        $query = $this->model->orderBy('created_at', 'desc');

        return $this->withRelations($query, $relations)->get();
    }

    /**
     * Find a permission by its ID.
     */
    public function find($id, ...$relations)
    {
        $query = $this->model->where('id', $id);

        return $this->withRelations($query, $relations)->first();
    }

    /**
     * Create a new permission.
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * Update a permission by its ID.
     */
    public function update($id, array $data)
    {
        $permission = $this->model->find($id);

        if ($permission) {
            $permission->update($data);
            return $permission;
        }
        
        return null;
    }

    /**
     * Delete a permission by its ID.
     */
    public function delete($id)
    {
        $permission = $this->model->find($id);

        if ($permission) {
            return $permission->delete();
        }
        
        return false;
    }
}