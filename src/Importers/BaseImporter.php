<?php

namespace Modules\ModuleRelease2\Importers;

use Illuminate\Http\UploadedFile;
use Modules\ModuleRelease2\Services\Shared\ImportService;

abstract class BaseImporter
{
   public function __construct(
        protected ImportService $importService,
        protected ?\Closure $logCallback = null,
    ){}

    abstract public function import(UploadedFile $file): array;

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
}