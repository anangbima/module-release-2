<?php

namespace Modules\DesaModuleTemplate\Console\Commands;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Console\Command;

class MakeComponentCommand extends Command
{
    /**
     * Signature of the command.
     * Harus di-inisialisasi LANGSUNG untuk hindari error typed property.
     */
    protected $signature;

    /**
     * Description of the command.
     */
    protected $description = 'Generate a new component file in the Desa Module Template module';

    public function __construct()
    {
        $this->signature = 'module:desamoduletemplate:make-component
            {name : Controller name with optional path, e.g. ProductComponent}';

        parent::__construct();
    }

    public function handle()
    {
        $rawName = $this->argument('name');

        $pathParts = explode('/', str_replace('\\', '/', $rawName));
        $className = Str::studly(array_pop($pathParts));
        $subPath = implode('/', array_map([Str::class, 'studly'], $pathParts));
        $namespaceSuffix = implode('\\', array_map([Str::class, 'studly'], $pathParts));

        $namespace = 'Modules\\DesaModuleTemplate\\View\\Components' . ($namespaceSuffix ? "\\$namespaceSuffix" : '');
        $basePath = base_path('modules/desa-module-template/src/View/Components' . ($subPath ? '/' . $subPath : ''));
        $filePath = "$basePath/{$className}.php";

        if (File::exists($filePath)) {
            $this->error("❌ Component {$className} already exists at {$filePath}");
            return;
        }

        // Ensure directory exists
        File::ensureDirectoryExists($basePath);

        // Load stub
        $stubPath = __DIR__ . '/../../../stubs/component.stub';
        if (!File::exists($stubPath)) {
            $this->error("❌ Stub file not found at {$stubPath}");
            return;
        }

        $stub = File::get($stubPath);

        // Replace placeholders
        $content = str_replace(
            ['{{ namespace }}', '{{ class }}'],
            [$namespace, $className],
            $stub
        );

        // Write to file
        File::put($filePath, $content);

        $this->newLine();
        $this->info("✅ Component {$className} created at {$filePath}");
        $this->newLine();
    }

}
