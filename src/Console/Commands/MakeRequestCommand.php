<?php

namespace Modules\ModuleRelease2\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class MakeRequestCommand extends Command
{
    /**
     * Signature & description
     */
    protected $signature;
    protected $description = 'Generate a new Form Request file in the Module Release 2 module';

    public function __construct()
    {
        $this->signature = 'module:modulerelease2:make-request
            {name : Request class name with optional path, e.g. Web/Admin/ProductRequest}';

        parent::__construct();
    }

    public function handle(): int
    {
        $rawName = $this->argument('name');

        // Parse path dan nama class
        $pathParts = explode('/', str_replace('\\', '/', $rawName));
        $className = Str::studly(array_pop($pathParts));
        $subPath = implode('/', array_map([Str::class, 'studly'], $pathParts));
        $namespaceSuffix = implode('\\', array_map([Str::class, 'studly'], $pathParts));

        $namespace = "Modules\\ModuleRelease2\\Http\\Requests" . ($namespaceSuffix ? "\\$namespaceSuffix" : '');
        $basePath = base_path('Modules/module-release-2/src/Http/Requests' . ($subPath ? '/' . $subPath : ''));
        $filePath = "$basePath/{$className}.php";

        File::ensureDirectoryExists($basePath);

        if (File::exists($filePath)) {
            $this->newLine();
            $this->error("❌ Request {$className} already exists at {$filePath}");
            $this->newLine();
            return 1;
        }

        $stubPath = __DIR__ . '/../../../stubs/request.stub';
        if (!File::exists($stubPath)) {
            $this->newLine();
            $this->error("❌ Stub not found at: {$stubPath}");
            $this->newLine();
            return 1;
        }

        $stub = str_replace(
            ['{{namespace}}', '{{class}}'],
            [$namespace, $className],
            File::get($stubPath)
        );

        File::put($filePath, $stub);
        $this->newLine();
        $this->info("✅ Request {$className} created successfully at {$filePath}");
        $this->newLine();
        return 0;
    }
}
