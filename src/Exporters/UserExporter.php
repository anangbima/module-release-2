<?php 

namespace Modules\DesaModuleTemplate\Exporters;

use Illuminate\Support\Collection;
use Modules\DesaModuleTemplate\Services\Shared\ExportService;

class UserExporter extends BaseExporter
{
    protected Collection $data;

    public function __construct(ExportService $exportService, Collection $data, )
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
        return ['ID', 'Name', 'Email', 'Role', 'Status', 'Province', 'City', 'District', 'Village'];
    }

    public function getMapper(): ?\Closure
    {
        return fn($row) => array_values($row);
    }
}