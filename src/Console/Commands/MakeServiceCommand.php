<?php

namespace Modules\ModuleRelease2\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeServiceCommand extends Command
{
    /**
     * Deskripsi command.
     *
     * @var string
     */
    protected $signature;
    protected $description = 'Generate a new service class in the Module Release 2 module';

    public function __construct()
    {
        $this->signature = 'module:modulerelease2:make-service
            {name : Service class name with optional path, e.g. Admin/ProductService}';

        parent::__construct();
    }

    public function handle(): int
    {
        $rawName = $this->argument('name');

        // Pisahkan path dan nama class
        $pathParts = explode('/', str_replace('\\', '/', $rawName));
        $className = Str::studly(array_pop($pathParts));
        $subPath = implode('/', array_map([Str::class, 'studly'], $pathParts));
        $namespaceSuffix = implode('\\', array_map([Str::class, 'studly'], $pathParts));

        $namespace = "Modules\\ModuleRelease2\\Services" . ($namespaceSuffix ? "\\$namespaceSuffix" : '');
        $basePath = base_path('Modules/module-release-2/src/Services' . ($subPath ? '/' . $subPath : ''));
        $filePath = "$basePath/{$className}.php";

        // Buat folder jika belum ada
        File::ensureDirectoryExists($basePath);

        // Cegah overwrite
        if (File::exists($filePath)) {
            $this->newLine();
            $this->error("❌ Service {$className} already exists at {$filePath}");
            $this->newLine();
            return 1;
        }

        // Ambil stub
        $stubPath = __DIR__ . '/../../../stubs/service.stub';
        if (!File::exists($stubPath)) {
            $this->newLine();
            $this->error("❌ Stub not found at: {$stubPath}");
            $this->newLine();
            return 1;
        }

        $stub = File::get($stubPath);
        $stub = str_replace(
            ['{{namespace}}', '{{class}}'],
            [$namespace, $className],
            $stub
        );

        // Simpan file service
        File::put($filePath, $stub);
        $this->newLine();
        $this->info("✅ Service {$className} created successfully at {$filePath}");
        $this->newLine();

        return 0;
    }
}
