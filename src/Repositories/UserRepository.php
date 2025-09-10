<?php

namespace Modules\ModuleRelease2\Repositories;

use Modules\ModuleRelease2\Models\User;
use Modules\ModuleRelease2\Repositories\Interfaces\UserRepositoryInterface;
use Modules\ModuleRelease2\Traits\HasRelationable;

class UserRepository implements UserRepositoryInterface
{
    use HasRelationable;

    protected $model;

    public function __construct()
    {
        $this->model = new User();
    }

    /**
     * Get all users.
     */
    public function all(...$relations)
    {
        $query = $this->model->orderBy('created_at', 'desc');

        return $this->withRelations($query, $relations)->get();
    }

    /**
     * Find a user by its ID.
     */
    public function find($id, ...$relations)
    {
        $query = $this->model->where('id', $id);

        return $this->withRelations($query, $relations)->first();
    }

    /**
     * Create a new user.
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * Update a user by its ID.
     */
    public function update($id, array $data)
    {
        $user = $this->model->find($id);

        if (!$user) {
            return false;
        }

        $user->update($data);
        return $user;
    }

    /**
     * Delete a user by its ID.
     */
    public function delete($id)
    {
        $user = $this->model->find($id);

        if (!$user) {
            return false;
        }

        return $user->delete();
    }

    /**
     * Check if a user exists by email.
     */
    public function checkByEmail(string $email): bool
    {
        return $this->model->where('email', $email)->exists();
    }

    /**
     * Count total data by status
     */
    public function countByStatus(string $status): int
    {
        return $this->model->where('status', $status)->count();
    }

}
