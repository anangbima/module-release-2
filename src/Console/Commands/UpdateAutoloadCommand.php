<?php

namespace Modules\ModuleRelease2\Console\Commands;

use Illuminate\Console\Command;

class UpdateAutoloadCommand extends Command
{
    /**
     * The console command description.
     *
     * @var string
     */
    protected $signature;
    protected $description = 'Backup and update composer.json to register PSR-4 for Module Release 2';

    public function __construct()
    {
        $this->signature = 'module:modulerelease2:update-autoload';
        parent::__construct();
    }

    /**
     * Execute the console command.
     * 
     * @return void
     */
    public function handle(): void
    {
        $this->newLine();
        $this->info('🔧 Updating composer.json...');

        $composerPath = base_path('composer.json');
        $backupPath = base_path('composer.backup.json');

        // Backup composer.json
        if (!file_exists($backupPath)) {
            copy($composerPath, $backupPath);
            $this->info('📦 composer.json backed up to composer.backup.json');
        }

        // Load and decode composer.json
        $composerJson = json_decode(file_get_contents($composerPath), true);
        if (!$composerJson) {
            $this->error('❌ Failed to parse composer.json');
            return;
        }

        // Target namespace dan path
        $namespace = 'Modules\\ModuleRelease2\\';
        $path = 'modules/module-release-2/src/';

        // Tambahkan ke psr-4 jika belum ada
        $psr4 =& $composerJson['autoload']['psr-4'];
        if (!array_key_exists($namespace, $psr4)) {
            $psr4[$namespace] = $path;

            // Simpan kembali composer.json
            file_put_contents($composerPath, json_encode($composerJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            $this->info('✅ composer.json updated.');

            // Jalankan dump-autoload
            $this->info('🔁 Running composer dump-autoload...');
            $this->newLine();
            exec('composer dump-autoload');
        } else {
            $this->info('ℹ️ Namespace already exists in composer.json. Skipped.');
            $this->newLine();
        }

        $this->newLine();
        $this->info('✅ Autoload update completed for Module Release 2 .');
    }
}