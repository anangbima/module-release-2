<?php

namespace Modules\ModuleRelease2\Repositories;

use Modules\ModuleRelease2\Models\LogActivity;
use Modules\ModuleRelease2\Repositories\Interfaces\LogActivityRepositoryInterface;
use Modules\ModuleRelease2\Traits\HasRelationable;

class LogActivityRepository implements LogActivityRepositoryInterface
{
    use HasRelationable;

    protected $model;

    public function __construct()
    {
        $this->model = new LogActivity();
    }

    /**
     * Retrieve all log activities.
     */
    public function all(...$relations)
    {
        $query = $this->model->orderBy('created_at', 'desc');

        return $this->withRelations($query, $relations)->get();
    }

    /**
     * Find a log activity by ID.
     */
    public function find(string $id, ...$relations)
    {
        $query = $this->model->where('id', $id);

        return $this->withRelations($query, $relations)->first();
    }

    /**
     * Create a new log activity.
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * Retrieve all log activities by role.
     */
    public function allByRole(string $roleId)
    {
        return $this->model
                        ->whereHas('user.roles', function ($query) use ($roleId) {
                            $query->where('id', $roleId);
                        })->orderBy('created_at', 'desc')->get();
    }

    /**
     * Retrieve all log activities by user ID.
     */
    public function allByUser(string $userId)
    {
        return $this->model->where('user_id', $userId)->orderBy('created_at', 'desc')->get();
    }

    // Get lates activty
    public function latest(int $limit = 10)
    {
        return $this->model
                ->orderBy('logged_at', 'desc')
                ->limit($limit)
                ->get();
    }
}