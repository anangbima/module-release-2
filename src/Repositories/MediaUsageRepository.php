<?php

namespace Modules\DesaModuleTemplate\Repositories;

use Modules\DesaModuleTemplate\Models\MediaUsage;
use Modules\DesaModuleTemplate\Repositories\Interfaces\MediaUsageRepositoryInterface;

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