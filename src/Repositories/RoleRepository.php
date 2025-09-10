<?php

namespace Modules\DesaModuleTemplate\Repositories;

use Modules\DesaModuleTemplate\Models\Role;
use Modules\DesaModuleTemplate\Repositories\Interfaces\RoleRepositoryInterface;
use Modules\DesaModuleTemplate\Traits\HasRelationable;

class RoleRepository implements RoleRepositoryInterface
{
    use HasRelationable;

    protected $model;

    public function __construct()
    {
        $this->model = new Role();
    }

    /**
     * Get all roles.
     */
    public function all(...$relations)
    {
        $query = $this->model->orderBy('created_at', 'desc');

        return $this->withRelations($query, $relations)->get();
    }

    /**
     * Find a role by its ID.
     */
    public function find($id, ...$relations)
    {
        $query = $this->model->where('id', $id);

        return $this->withRelations($query, $relations)->first();
    }

    /**
     * Create a new role.
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * Update a role by its ID.
     */
    public function update($id, array $data)
    {
        $role = $this->model->find($id);

        if ($role) {
            $role->update($data);
            return $role;
        }
        
        return null;
    }

    /**
     * Delete a role by its ID.
     */
    public function delete($id)
    {
        $role = $this->model->find($id);

        if ($role) {
            $role->delete();
            return true;
        }

        return false;
    }
}
