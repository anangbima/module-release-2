<?php

namespace Modules\ModuleRelease2\Repositories;

use Modules\ModuleRelease2\Models\MediaUsage;
use Modules\ModuleRelease2\Repositories\Interfaces\MediaUsageRepositoryInterface;

class MediaUsageRepository implements MediaUsageRepositoryInterface
{
    protected $model;

    public function __construct()
    {
        $this->model = new MediaUsage();
    }

    /**
     * Create a new media usage record.
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }
}