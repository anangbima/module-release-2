<?php

namespace Modules\DesaModuleTemplate\Exporters;

use Illuminate\Support\Collection;
use Modules\DesaModuleTemplate\Services\Shared\ExportService;

class LogActivityExporter extends BaseExporter
{
    protected Collection $data;

    public function __construct(ExportService $exportService, Collection $data)
    {
        parent::__construct($exportService);
        $this->data = $data;
    }

    public function getData(): Collection
    {
        return $this->data;
    }

    public function getHeadings(): array
    {
        return ['ID', 'User ID', 'User Name', 'Action', 'Description', 'IP Address', 'Created At', 'Updated At'];
    }

    public function getMapper(): ?\Closure
    {
        return fn($row) => array_values($row);
    }
}
