<?php

namespace Modules\ModuleRelease2\Console\Commands;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Console\Command;

class MakeControllerCommand extends Command
{
    /**
     * Signature of the command.
     * Harus di-inisialisasi LANGSUNG untuk hindari error typed property.
     */
    protected $signature;

    /**
     * Description of the command.
     */
    protected $description = 'Generate a new controller file in the Module Release 2 module';

    public function __construct()
    {
        $this->signature = 'module:modulerelease2:make-controller
            {name : Controller name with optional path, e.g. Admin/ProductController}';

        parent::__construct();
    }

    public function handle()
    {
        $rawName = $this->argument('name');

        $pathParts = explode('/', str_replace('\\', '/', $rawName));
        $className = Str::studly(array_pop($pathParts));
        $subPath = implode('/', array_map([Str::class, 'studly'], $pathParts));
        $namespaceSuffix = implode('\\', array_map([Str::class, 'studly'], $pathParts));

        $namespace = 'Modules\\ModuleRelease2\\Http\\Controllers' . ($namespaceSuffix ? "\\$namespaceSuffix" : '');
        $basePath = base_path('Modules/module-release-2/src/Http/Controllers' . ($subPath ? '/' . $subPath : ''));
        $filePath = "$basePath/{$className}.php";
        $controllerNamespace = 'Modules\\ModuleRelease2\\Http\\Controllers';

        if (!File::exists($basePath)) {
            File::makeDirectory($basePath, 0755, true);
        }

        if (File::exists($filePath)) {
            $this->newLine();
            $this->error("❌ Controller {$className} already exists at {$filePath}");
            $this->newLine();
            return;
        }

        $stubPath = __DIR__ . '/../../../stubs/controller.stub';
        if (!File::exists($stubPath)) {
            $this->newLine();
            $this->error("❌ Stub not found at: {$stubPath}");
            $this->newLine();
            return;
        }

        $stub = File::get($stubPath);
        $stub = str_replace(['{{namespace}}', '{{class}}', '{{controller_namespace}}'], [$namespace, $className, $controllerNamespace], $stub);

        File::put($filePath, $stub);

        $this->newLine();
        $this->info("✅ Controller {$className} created successfully at {$filePath}");
        $this->newLine();

    }
}
