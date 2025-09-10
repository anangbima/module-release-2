<?php

namespace Modules\ModuleRelease2\Exporters;

use Illuminate\Support\Collection;
use Modules\ModuleRelease2\Services\Shared\ExportService;

abstract class BaseExporter
{
    public function __construct(
        protected ExportService $exportService,
        protected ?\Closure $logCallback = null,
    ){}

    abstract public function getData(): Collection;

    public function getHeadings(): array
    {
        return [];
    }

    public function getMapper(): ?\Closure
    {
        return null;
    }

    public function setLogCallback(?\Closure $callback): static
    {
        $this->logCallback = $callback;
        return $this;
    }

    protected function log(string $format, string $fileName): void
    {
        if ($this->logCallback) {
            ($this->logCallback)($format, $fileName);
        }
    }

    public function exportToExcel(string $fileName, string $format = 'xlsx')
    {
        $result = $this->exportService->exportToExcel(
            data: $this->getData(),
            headings: $this->getHeadings(),
            fileName: $fileName,
            mapper: $this->getMapper(),
            format: $format
        ); 

        $this->log($format, $fileName);

        return $result;
    }

    public function exportToPdf(string $fileName, string $view, array $viewData = [], array $paperSize = ['A4', 'landscape'])
    {
        $result = $this->exportService->exportToPdf(
            data: $this->getData(),
            view: $view,
            fileName: $fileName,
            viewData: $viewData,
            paperSize: $paperSize
        );

        $this->log('pdf', $fileName);

        return $result;
    }

    public function exportToDocx(string $fileName)
    {
        $result = $this->exportService->exportToDocx(
            data: $this->getData()->toArray(),
            fileName: $fileName
        );

        $this->log('docx', $fileName);

        return $result;
    }

    public function exportToJson(string $fileName)
    {
        $result = $this->exportService->exportToJson(
            data: $this->getData(),
            fileName: $fileName
        );

        $this->log('json', $fileName);

        return $result;
    }
}