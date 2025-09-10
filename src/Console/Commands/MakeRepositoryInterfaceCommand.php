<?php

namespace Modules\ModuleRelease2\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeRepositoryInterfaceCommand extends Command
{
    /**
     * Description Command.
     *
     * @var string
     */
    protected $signature;
    protected $description = 'Generate a new repository interface file in the Module Release 2 module';

    public function __construct()
    {
        $this->signature = 'module:modulerelease2:make-repository-interface
            {name : Interface name with optional path, e.g. Product/ProductRepositoryInterface}';

        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $rawName = $this->argument('name');

        $pathParts = explode('/', str_replace('\\', '/', $rawName));
        $className = Str::studly(array_pop($pathParts));
        $subPath = implode('/', array_map([Str::class, 'studly'], $pathParts));
        $namespaceSuffix = implode('\\', array_map([Str::class, 'studly'], $pathParts));

        $namespace = 'Modules\\ModuleRelease2\\Repositories\\Interfaces' . ($namespaceSuffix ? "\\$namespaceSuffix" : '');
        $basePath = base_path('Modules/module-release-2/src/Repositories/Interfaces' . ($subPath ? "/$subPath" : ''));
        $filePath = "$basePath/{$className}.php";

        if (File::exists($filePath)) {
            $this->newLine();
            $this->error("❌ Interface {$className} already exists at {$filePath}");
            $this->newLine();
            return 1;
        }

        File::ensureDirectoryExists($basePath);

        $stubPath = __DIR__ . '/../../../stubs/repository-interface.stub';
        if (!File::exists($stubPath)) {
            $this->error("❌ Stub not found at: {$stubPath}");
            return 1;
        }

        $stub = str_replace(
            ['{{namespace}}', '{{class}}'],
            [$namespace, $className],
            File::get($stubPath)
        );

        File::put($filePath, $stub);
        $this->newLine();
        $this->info("✅ Repository interface {$className} created successfully at {$filePath}");
        $this->newLine();
        return 0;
    }
}
