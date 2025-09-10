<?php

namespace Modules\ModuleRelease2\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeTraitCommand extends Command
{
    /**
     * Deskripsi command.
     *
     * @var string
     */
    protected $signature;
    protected $description = 'Generate a new traits class in the Module Release 2 module';

    public function __construct()
    {
        $this->signature = 'module:modulerelease2:make-trait
            {name : Trait class name with optional path, e.g. HasApiToken}';

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

        $namespace = "Modules\\ModuleRelease2\\Traits" . ($namespaceSuffix ? "\\$namespaceSuffix" : '');
        $basePath = base_path('Modules/module-release-2/src/Traits' . ($subPath ? '/' . $subPath : ''));
        $filePath = "$basePath/{$className}.php";

        // Buat folder jika belum ada
        File::ensureDirectoryExists($basePath);

        // Cegah overwrite
        if (File::exists($filePath)) {
            $this->newLine();
            $this->error("❌ Trait {$className} already exists at {$filePath}");
            $this->newLine();
            return 1;
        }

        // Ambil stub
        $stubPath = __DIR__ . '/../../../stubs/trait.stub';
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

        // Simpan file trait
        File::put($filePath, $stub);
        $this->newLine();
        $this->info("✅ Trait {$className} created successfully at {$filePath}");
        $this->newLine();

        return 0;
    }
}
