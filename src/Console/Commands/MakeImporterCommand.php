<?php

namespace Modules\DesaModuleTemplate\Console\Commands;

use Illuminate\Console\Command;

class MakeImporterCommand extends Command
{
    protected $signature;
    protected $description = 'Generate a new importer for model Desa Module Template';

    public function __construct()
    {
        $this->signature = 'module:desamoduletemplate:make-importer
            {name : Importer name (e.g. TableImporter)}';

        parent::__construct();
    }

    public function handle()
    {
        $importerName = $this->argument('name'); // misalnya: Table1Importer
        $className = $importerName;              // Table1Importer

        $namespace = 'Modules\\DesaModuleTemplate\\Importers';
        $targetPath = base_path('Modules/desa-module-template/src/Importers/'.$className.'.php');
        $stubPath = __DIR__ . '/../../../stubs/importer.stub';

        if (!file_exists($stubPath)) {
            $this->newLine();
            $this->error("❌ Importer stub not found at: {$stubPath}");
            $this->newLine();
            return;
        }

        if (file_exists($targetPath)) {
            $this->newLine();
            $this->warn("⚠️ Importer already exists at: {$targetPath}");
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
        $this->info("✅ Importer created at: {$targetPath}");
        $this->newLine();
    }
}