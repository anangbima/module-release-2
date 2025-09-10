<?php

namespace Modules\ModuleRelease2\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeMiddlewareCommand extends Command
{
    protected $signature;
    protected $description = 'Generate a new middleware for Module Release 2';

    public function __construct()
    {
        $this->signature = 'module:modulerelease2:make-middleware
            {name : Middleware name (e.g. EnsureAdmin)}';

        parent::__construct();
    }

    public function handle()
    {
        $className = Str::studly($this->argument('name')); // EnsureAdmin
        $namespace = 'Modules\\ModuleRelease2\\Http\\Middleware';
        $targetPath = base_path('Modules/module-release-2/src/Http/Middleware/'.$className.'.php');
        $stubPath = __DIR__ . '/../../../stubs/middleware.stub';

        File::ensureDirectoryExists(dirname($targetPath));

        if (!File::exists($stubPath)) {
            $this->newLine();
            $this->error("❌ Middleware stub not found at: {$stubPath}");
            $this->newLine();
            return;
        }

        if (File::exists($targetPath)) {
            $this->newLine();
            $this->warn("⚠️ Middleware already exists at: {$targetPath}");
            $this->newLine();
            return;
        }

        $stub = str_replace(
            ['{{namespace}}', '{{class}}'],
            [$namespace, $className],
            File::get($stubPath)
        );

        File::put($targetPath, $stub);
        $this->newLine();
        $this->info("✅ Middleware created at: {$targetPath}");
        $this->newLine();
    }
}
