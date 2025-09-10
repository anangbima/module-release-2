<?php

namespace Modules\DesaModuleTemplate\Console\Commands;

use Illuminate\Console\Command;

class MakeExporterCommand extends Command
{
    protected $signature;
    protected $description = 'Generate a new exporter for model Desa Module Template';

    public function __construct()
    {
        $this->signature = 'module:desamoduletemplate:make-exporter
            {name : Exporter name (e.g. TableExporter)}';

        parent::__construct();
    }

    public function handle()
    {
        $exporterName = $this->argument('name'); // misalnya: Table1Exporter
        $className = $exporterName;              // Table1Exporter

        $namespace = 'Modules\\DesaModuleTemplate\\Exporters';
        $targetPath = base_path('Modules/desa-module-template/src/Exporters/'.$className.'.php');
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