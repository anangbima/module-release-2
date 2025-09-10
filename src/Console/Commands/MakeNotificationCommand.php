<?php

namespace Modules\DesaModuleTemplate\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeNotificationCommand extends Command
{
    protected $signature;
    protected $description = 'Generate a new notification class using stub for Desa Module Template';

    public function __construct()
    {
        $this->signature = 'module:desamoduletemplate:make-notification
            {name : Notification class name (e.g. ResetPasswordNotification)}';

        parent::__construct();
    }

    public function handle()
    {
        $name = Str::studly($this->argument('name'));
        $className = $name;

        $namespace = 'Modules\\DesaModuleTemplate\\Notifications';
        $targetPath = base_path('Modules/desa-module-template/src/Notifications/'.$className.'.php');
        $stubPath = __DIR__ . '/../../../stubs/notification.stub';

        File::ensureDirectoryExists(dirname($targetPath));

        if (!File::exists($stubPath)) {
            $this->newLine();
            $this->error("❌ Notification stub not found at: {$stubPath}");
            $this->newLine();
            return;
        }

        if (File::exists($targetPath)) {
            $this->newLine();
            $this->warn("⚠️ Notification already exists at: {$targetPath}");
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
        $this->info("✅ Notification created at: {$targetPath}");
        $this->newLine();
    }
}
