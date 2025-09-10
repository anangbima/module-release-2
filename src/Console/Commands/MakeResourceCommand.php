<?php

namespace Modules\ModuleRelease2\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeResourceCommand extends Command
{
    protected $signature;
    protected $description = 'Generate a new API resource for Module Release 2';

    public function __construct()
    {
        $this->signature = 'module:modulerelease2:make-resource
            {name : Model name (e.g. Product)}';

        parent::__construct();
    }

    public function handle()
    {
        $input = str_replace('\\', '/', $this->argument('name')); // normalize
        $parts = explode('/', $input);

        $rawClassName = array_pop($parts);               // e.g. UserResource
        $className = Str::studly($rawClassName);         // ensure pascal case
        $modelVar = Str::camel(str_replace('Resource', '', $className)); // e.g. user

        $subNamespace = implode('\\', array_map('Str::studly', $parts)); // e.g. User
        $subPath = implode('/', array_map('Str::studly', $parts));       // e.g. User

        $namespace = 'Modules\\ModuleRelease2\\Http\\Resources' . ($subNamespace ? "\\{$subNamespace}" : '');
        $targetPath = base_path("Modules/module-release-2/src/Http/Resources"
            . ($subPath ? "/{$subPath}" : '')
            . "/{$className}.php");

        $stubPath = __DIR__ . '/../../../stubs/resource.stub';

        File::ensureDirectoryExists(dirname($targetPath));

        if (!File::exists($stubPath)) {
            $this->newLine();
            $this->error("❌ Resource stub not found at: {$stubPath}");
            $this->newLine();
            return;
        }

        if (File::exists($targetPath)) {
            $this->newLine();
            $this->warn("⚠️ Resource already exists at: {$targetPath}");
            $this->newLine();
            return;
        }

        $stub = str_replace(
            ['{{namespace}}', '{{class}}', '{{model_variable}}'],
            [$namespace, $className, $modelVar],
            File::get($stubPath)
        );

        File::put($targetPath, $stub);
        $this->newLine();
        $this->info("✅ Resource created at: {$targetPath}");
        $this->newLine();
    }

}
