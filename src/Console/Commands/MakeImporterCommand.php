<?php

namespace Modules\ModuleRelease2\Console\Commands;

use Illuminate\Console\Command;

class MakeImporterCommand extends Command
{
    protected $signature;
    protected $description = 'Generate a new importer for model Module Release 2';

    public function __construct()
    {
        $this->signature = 'module:modulerelease2:make-importer
            {name : Importer name (e.g. TableImporter)}';

        parent::__construct();
    }

    public function handle()
    {
        $importerName = $this->argument('name'); // misalnya: Table1Importer
        $className = $importerName;              // Table1Importer

        $namespace = 'Modules\\ModuleRelease2\\Importers';
        $targetPath = base_path('Modules/module-release-2/src/Importers/'.$className.'.php');
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