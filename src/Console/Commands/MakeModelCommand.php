<?php

namespace Modules\DesaModuleTemplate\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeModelCommand extends Command
{
    protected $signature;
    protected $description = 'Generate a new model and migration for Desa Module Template';

    public function __construct()
    {
        $this->signature = 'module:desamoduletemplate:make-model
            {name : Model name (e.g. Table1)}';

        parent::__construct();
    }

    public function handle(): int
    {
        $rawName = trim($this->argument('name'));
        $modelName = Str::studly($rawName);
        $tableName = Str::snake(Str::pluralStudly($modelName));

        $modelDir = base_path('Modules/desa-module-template/src/Models');
        $migrationDir = base_path('Modules/desa-module-template/src/Database/Migrations');
        $moduleNamespace = 'Modules\\DesaModuleTemplate\\';

        $modelPath = "$modelDir/$modelName.php";

        if (!File::exists($modelDir)) File::makeDirectory($modelDir, 0755, true);
        if (!File::exists($migrationDir)) File::makeDirectory($migrationDir, 0755, true);

        if (File::exists($modelPath)) {
            $this->newLine();
            $this->error("❌ Model {$modelName} already exists.");
            $this->newLine();
            return 1;
        }

        // ==== MODEL ====
        $modelStubPath = __DIR__ . '/../../../stubs/model.stub';
        if (!File::exists($modelStubPath)) {
            $this->newLine();
            $this->error("❌ model.stub not found at $modelStubPath");
            $this->newLine();
            return 1;
        }

        $modelStub = File::get($modelStubPath);
        $modelStub = str_replace(
            ['{{namespace}}', '{{class}}', '{{config_key}}', '{{table_key}}', '{{fallback}}'],
            ['Modules\\DesaModuleTemplate\\Models', $modelName, 'desa_module_template', $tableName, $tableName],
            $modelStub
        );
        File::put($modelPath, $modelStub);

        $this->newLine();
        $this->info("✅ Model created: $modelPath");
        $this->newLine();

        // ==== MIGRATION ====
        $migrationClass = 'Create' . Str::studly($tableName) . 'Table';
        $migrationFileName = date('Y_m_d_His') . "_create_{$tableName}_table.php";
        $migrationPath = "$migrationDir/$migrationFileName";

        $migrationStubPath = __DIR__ . '/../../../stubs/migration.stub';
        if (!File::exists($migrationStubPath)) {
            $this->newLine();
            $this->error("❌ migration.stub not found at $migrationStubPath");
            $this->newLine();
            return 1;
        }

        $migrationStub = File::get($migrationStubPath);
        $migrationStub = str_replace(
            ['{{class}}', '{{config_key}}', '{{table_key}}', '{{fallback}}', '{{module_namespace}}'],
            [$migrationClass, desa_module_template_meta('snake'), 'table_name', $tableName, $moduleNamespace],
            $migrationStub
        );        
        File::put($migrationPath, $migrationStub);

        $this->info("✅ Migration created: $migrationPath");
        $this->newLine();

        // ==== CONFIG OVERRIDE ====
        $configFolder = base_path('Modules/desa-module-template/config/'.$tableName);
        $uploadConfigPath = "$configFolder/upload.php";
        $tableConfigPath = "$configFolder/table.php";

        $uploadStubPath = __DIR__ . '/../../../stubs/upload.stub';
        $tableStubPath = __DIR__ . '/../../../stubs/table.stub';

        if (!File::exists($configFolder)) {
            File::makeDirectory($configFolder, 0755, true);
        }

        // Generate upload.php
        if (!File::exists($uploadStubPath)) {
            $this->newLine();
            $this->warn("⚠️  upload.stub not found at $uploadStubPath, skipping upload config.");
            $this->newLine();
        } else {
            if (!File::exists($uploadConfigPath)) {
                $stub = File::get($uploadStubPath);
                $stub = str_replace('{{table}}', $tableName, $stub);
                File::put($uploadConfigPath, $stub);
                $this->newLine();
                $this->info("✅ upload.php config created: $uploadConfigPath");
                $this->newLine();
            } else {
                $this->newLine();
                $this->warn("⚠️  upload.php already exists: $uploadConfigPath");
                $this->newLine();
            }
        }

        // Generate tables.php
        if (!File::exists($tableStubPath)) {
            $this->newLine();
            $this->warn("⚠️  table.stub not found at $tableStubPath, skipping table config.");
            $this->newLine();
        } else {
            if (!File::exists($tableConfigPath)) {
                $stub = File::get($tableStubPath);
                $stub = str_replace('{{table}}', $tableName, $stub);
                File::put($tableConfigPath, $stub);
                $this->newLine();
                $this->info("✅ tables.php config created: $tableConfigPath");
                $this->newLine();
            } else {
                $this->newLine();
                $this->warn("⚠️  table.php already exists: $tableConfigPath");
                $this->newLine();
            }
        }


        return 0;
    }
}
