<?php

namespace Modules\DesaModuleTemplate\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class MakeRepositoryCommand extends Command
{
    protected $signature;
    protected $description = 'Generate a new repository and optionally its interface in the Desa Module Template module';

    public function __construct()
    {
        $this->signature = 'module:desamoduletemplate:make-repository
            {name : Repository name, e.g. ProductRepository}
            {--with-interface : Also generate interface if not exists}';

        parent::__construct();
    }

    public function handle(): int
    {
        $className = Str::studly($this->argument('name'));
        $interfaceName = $className . 'Interface';

        $repoNamespace = 'Modules\\DesaModuleTemplate\\Repositories';
        $interfaceNamespace = 'Modules\\DesaModuleTemplate\\Repositories\\Interfaces';

        $repoPath = base_path('Modules/desa-module-template/src/Repositories/'.$className.'.php');
        $interfacePath = base_path('Modules/desa-module-template/src/Repositories/Interfaces/'.$interfaceName.'.php');

        File::ensureDirectoryExists(dirname($repoPath));
        File::ensureDirectoryExists(dirname($interfacePath));

        $this->generateRepository($repoPath, $repoNamespace, $className, $interfaceNamespace, $interfaceName);

        if ($this->option('with-interface') || !File::exists($interfacePath)) {
            $this->generateInterface($interfacePath, $interfaceNamespace, $interfaceName);
        }

        $this->addBindingToTrait($interfaceNamespace, $interfaceName, $repoNamespace, $className);

        return 0;
    }

    protected function generateRepository(
        string $path,
        string $namespace,
        string $className,
        string $interfaceNamespace,
        string $interfaceName
    ): void {
        if (File::exists($path)) {
            $this->newLine();
            $this->warn("⚠️ Repository already exists at {$path}");
            $this->newLine();
            return;
        }

        $stubPath = __DIR__ . '/../../../stubs/repository.stub';
        if (!File::exists($stubPath)) {
            $this->newLine();
            $this->error("❌ Repository stub not found at: {$stubPath}");
            $this->newLine();
            return;
        }

        $stub = str_replace(
            ['{{namespace}}', '{{class}}', '{{interface_namespace}}', '{{interface}}'],
            [$namespace, $className, $interfaceNamespace, $interfaceName],
            File::get($stubPath)
        );

        File::put($path, $stub);
        $this->newLine();
        $this->info("✅ Repository created at {$path}");
        $this->newLine();
    }

    protected function generateInterface(string $path, string $namespace, string $interfaceName): void
    {
        if (File::exists($path)) {
            $this->newLine();
            $this->warn("⚠️ Interface already exists at {$path}");
            $this->newLine();
            return;
        }

        $stubPath = __DIR__ . '/../../../stubs/repository-interface.stub';
        if (!File::exists($stubPath)) {
            $this->newLine();
            $this->error("❌ Interface stub not found at: {$stubPath}");
            $this->newLine();
            return;
        }

        $stub = str_replace(
            ['{{namespace}}', '{{class}}'],
            [$namespace, $interfaceName],
            File::get($stubPath)
        );

        File::put($path, $stub);
        $this->info("✅ Interface created at {$path}");
        $this->newLine();
    }

    protected function addBindingToTrait(string $interfaceNamespace, string $interfaceName, string $repoNamespace, string $repoClass): void
    {
        $traitPath = base_path('Modules/desa-module-template/src/Providers/Concerns/BindRepositories.php');

        if (!File::exists($traitPath)) {
            $this->newLine();
            $this->error("❌ Trait BindRepositories not found at {$traitPath}");
            $this->newLine();
            return;
        }

        $bindLine = "\$this->app->bind(\\{$interfaceNamespace}\\{$interfaceName}::class, \\{$repoNamespace}\\{$repoClass}::class);";

        $content = File::get($traitPath);

        // Cegah duplikat
        if (str_contains($content, $bindLine)) {
            $this->newLine();
            $this->warn("⚠️ Binding already exists in BindRepositories.");
            $this->newLine();
            return;
        }

        // Tambahkan sebelum penutup method bindRepositories
        $newContent = preg_replace(
            '/protected function bindRepositories\(\): void\s*\{/',
            "protected function bindRepositories(): void\n    {\n        " . $bindLine,
            $content
        );

        File::put($traitPath, $newContent);

        $this->info("✅ Binding added to BindRepositories trait.");
        $this->newLine();
    }

}
