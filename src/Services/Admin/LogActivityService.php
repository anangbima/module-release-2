<?php

namespace Modules\DesaModuleTemplate\Services\Admin;

use Illuminate\Support\Collection;
use Modules\DesaModuleTemplate\Repositories\Interfaces\LogActivityRepositoryInterface;
use Modules\DesaModuleTemplate\Services\Shared\LogActivityService as SharedLogActivityService;

class LogActivityService extends SharedLogActivityService
{

    /**
     * Get all log activities wiith mapped data for exporting or displaying.
     */
    public function getMappedLogs(): Collection
    {
        return $this->logActivityRepository->all()->map(fn($log) => [
            'id' => $log->id,
            'user_id' => $log->user_id,
            'user_name' => $log->user->name ?? 'Unknown',
            'action' => $log->action,
            'description' => $log->description,
            'ip_address' => $log->ip_address,
            'created_at' => format_date($log->created_at),
            'updated_at' => format_date($log->updated_at),
        ]);
    }

    /**
     * Retrieve all log activities.
     */
    public function getAllLogs(...$relations)
    {
        return $this->logActivityRepository->all(...$relations);
    }

    /**
     * Get by role
     */
    public function getAllByRole(string $roleId, ...$relations)
    {
        return $this->logActivityRepository->allByRole($roleId, ...$relations);
    }
}