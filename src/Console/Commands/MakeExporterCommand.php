<?php

namespace Modules\ModuleRelease2\Console\Commands;

use Illuminate\Console\Command;

class MakeExporterCommand extends Command
{
    protected $signature;
    protected $description = 'Generate a new exporter for model Module Release 2';

    public function __construct()
    {
        $this->signature = 'module:modulerelease2:make-exporter
            {name : Exporter name (e.g. TableExporter)}';

        parent::__construct();
    }

    public function handle()
    {
        $exporterName = $this->argument('name'); // misalnya: Table1Exporter
        $className = $exporterName;              // Table1Exporter

        $namespace = 'Modules\\ModuleRelease2\\Exporters';
        $targetPath = base_path('Modules/module-release-2/src/Exporters/'.$className.'.php');
        $stubPath = __DIR__ . '/../../../stubs/exporter.stub';

        if (!file_exists($stubPath)) {
            $this->newLine();
            $this->error("❌ Exporter stub not found at: {$stubPath}");
            $this->newLine();
            return;
        }

        if (file_exists($targetPath)) {
            $this->newLine();
            $this->warn("⚠️ Exporter already exists at: {$targetPath}");
            $this->newLine();
            return;
        }

        $stub = str_replace(
            ['{{namespace}}', '{{class}}'],
            [$namespace, $className],
            file_get_contents($stubPath)
        );

        file_put_contents($targetPath, $stub);

        $this->newLine();
        $this->info("✅ Exporter created at: {$targetPath}");
        $this->newLine();
    }
}