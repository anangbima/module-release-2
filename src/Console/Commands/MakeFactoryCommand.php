<?php

namespace Modules\DesaModuleTemplate\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeFactoryCommand extends Command
{
    protected $signature;
    protected $description = 'Generate a new factory for model and migration for Desa Module Template';

    public function __construct()
    {
        $this->signature = 'module:desamoduletemplate:make-factory
            {name : Model name (e.g. Table1)}';

        parent::__construct();
    }

    public function handle()
    {
        $model = Str::studly($this->argument('name')); // misalnya: Product
        $className = $model . 'Factory';               // ProductFactory

        $namespace = 'Modules\\DesaModuleTemplate\\Database\\Factories';
        $targetPath = base_path('Modules/desa-module-template/src/Database/Factories/'.$className.'.php');
        $stubPath = __DIR__ . '/../../../stubs/factory.stub';
        $modelNamespace = 'Modules\\DesaModuleTemplate\\Models\\' . $model;

        File::ensureDirectoryExists(dirname($targetPath));

        if (!File::exists($stubPath)) {
            $this->newLine();
            $this->error("❌ Factory stub not found at: {$stubPath}");
            $this->newLine();
            return;
        }

        if (File::exists($targetPath)) {
            $this->newLine();
            $this->warn("⚠️ Factory already exists at: {$targetPath}");
            $this->newLine();
            return;
        }

        $stub = str_replace(
            ['{{namespace}}', '{{class}}', '{{model}}', '{{model_namespace}}'],
            [$namespace, $className, $model, $modelNamespace],
            File::get($stubPath)
        );

        File::put($targetPath, $stub);

        $this->newLine();
        $this->info("✅ Factory created at: {$targetPath}");
        $this->newLine();

    }
}