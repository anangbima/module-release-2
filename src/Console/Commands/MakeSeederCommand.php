<?php

namespace Modules\ModuleRelease2\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeSeederCommand extends Command
{
    protected $signature;
    protected $description = 'Generate a new seeder for Module Release 2';

    public function __construct()
    {
        $this->signature = 'module:modulerelease2:make-seeder
            {name : Model name (e.g. Product)}';

        parent::__construct();
    }

    public function handle()
    {
        $input = str_replace('\\', '/', $this->argument('name')); // normalize slashes
        $parts = explode('/', $input);

        $rawClassName = array_pop($parts);               // e.g. ProductSeeder
        $className = Str::studly($rawClassName);         // ProductSeeder

        // Ambil nama model dari className
        $modelName = str_replace('Seeder', '', $className);
        $model = Str::studly($modelName);
        $modelNamespace = 'Modules\\ModuleRelease2\\Models\\' . $model;

        // Buat sub namespace & path jika ada
        $subNamespace = implode('\\', array_map('Str::studly', $parts)); // e.g. User
        $subPath = implode('/', array_map('Str::studly', $parts));       // e.g. User

        $namespace = 'Modules\\ModuleRelease2\\Database\\Seeders' . ($subNamespace ? "\\{$subNamespace}" : '');
        $targetPath = base_path("Modules/module-release-2/src/Database/Seeders"
            . ($subPath ? "/{$subPath}" : '')
            . "/{$className}.php");

        $stubPath = __DIR__ . '/../../../stubs/seeder.stub';

        File::ensureDirectoryExists(dirname($targetPath));

        if (!File::exists($stubPath)) {
            $this->newLine();
            $this->error("❌ Seeder stub not found at: {$stubPath}");
            $this->newLine();
            return;
        }

        if (File::exists($targetPath)) {
            $this->newLine();
            $this->warn("⚠️ Seeder already exists at: {$targetPath}");
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
        $this->info("✅ Seeder created at: {$targetPath}");
        $this->newLine();
    }

}
