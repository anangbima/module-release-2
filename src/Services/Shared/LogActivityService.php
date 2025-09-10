<?php

namespace Modules\DesaModuleTemplate\Services\Shared;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Modules\DesaModuleTemplate\Repositories\Interfaces\LogActivityRepositoryInterface;

class LogActivityService
{
    public function __construct(
        protected LogActivityRepositoryInterface $logActivityRepository,
    ) {}

    /**
     * Create log activity data
     */
    public function log(
        string $action,
        $model = null,
        ?string $description = null,
        array $before = [],
        array $after = [],
    ){
        $data = [
            'action' => $action,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model?->id,
            'description' => $description,
            'user_id' => desa_module_template_auth_user()?->id ?? null,
            'logged_at' => now(),
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'data_before' => json_encode($before),
            'data_after' => json_encode($after),
        ];

        return $this->logActivityRepository->create($data);
    }

    /**
     * Get log activity by ID
     */
    public function getLogById(string $id)
    {
        return $this->logActivityRepository->find($id);
    }

    /**
     * Get all log activities by user ID
     */
    public function getAllLogsByUser(string $userId)
    {
        return $this->logActivityRepository->allByUser($userId);
    }

    /**
     * Get recent log activities
     */
    public function getRecentLogs(int $limit = 10)
    {
        return $this->logActivityRepository->latest($limit);
    }
}