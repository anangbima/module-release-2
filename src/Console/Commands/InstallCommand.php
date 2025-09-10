<?php 

namespace Modules\ModuleRelease2\Console\Commands;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    /**
     * The console command description.
     *
     * @var string
     */
    protected $signature;
    protected $description = 'Install Module Release 2';

    public function __construct()
    {
        // Set the command signature using the module prefix from the configuration
        // This allows the command to be namespaced under the module's prefix

        $this->signature = 'module:modulerelease2:install
                            {--fresh : Drop all tables first}
                            {--refresh : Reset and re-run all migrations}
                            {--seed : Seed the database}';

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {   
        $this->newLine();
        $this->info('Installing Module Release 2 ...');
        $this->newLine();
    
        // Register the service provider
        $this->call('module:modulerelease2:register-provider');

        // Add environment variables
        $this->call('module:modulerelease2:add-env');
        
        $connection = $this->getEnvValue('MODULE_RELEASE_2_DB_CONNECTION');
        
        config([
            'database.connections.' . $connection => [
                'driver' => $this->getEnvValue('MODULE_RELEASE_2_DB_DRIVER'),
                'host' => $this->getEnvValue('MODULE_RELEASE_2_DB_HOST'),
                'port' => $this->getEnvValue('MODULE_RELEASE_2_DB_PORT'),
                'database' => $this->getEnvValue('MODULE_RELEASE_2_DB_DATABASE'),
                'username' => $this->getEnvValue('MODULE_RELEASE_2_DB_USERNAME'),
                'password' => $this->getEnvValue('MODULE_RELEASE_2_DB_PASSWORD'),
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
            ],
            'database.default' => $connection,
        ]);

        if (!$connection) {
            $this->error('❌ MODULE_RELEASE_2_DB_CONNECTION not set in .env');
            return Command::FAILURE;
        }
            
        $this->newLine();
        $this->info("Using DB connection: $connection");

        if (app()->environment('test')) {
            $migrationPath = 'modules/module-release-2/src/Database/Migrations';
            $realMigrationPath = base_path($migrationPath);
        } else {
            $realMigrationPath = realpath('vendor/modules/module-release-2/src/Database/Migrations');
            $migrationPath = $realMigrationPath;
        }

        // Validasi jika path tidak ditemukan
        if (!is_dir($realMigrationPath)) {
            $this->error("❌ Migration path not found: $realMigrationPath");
            return Command::FAILURE;
        }
        
        $this->info("Looking in: $migrationPath");
        $this->info("Found migration files: " . count(glob($migrationPath . '/*.php')));
        $this->newLine();
        
        $arguments = [
            '--force' => true,
            '--database' => $connection,
            '--path' => $realMigrationPath,
            '--realpath' => true,
        ];
        
        if ($this->option('fresh')) {
            $this->call('migrate:fresh', [
                '--database' => $connection,
                '--path' => $migrationPath,
                '--force' => true,
            ]);
        } elseif ($this->option('refresh')) {
            $this->call('migrate:refresh', $arguments);
        } else {
            $this->call('migrate', $arguments);
        }
        
        // Optional: seed
        if ($this->option('seed')) {
            $this->call('db:seed', [
                '--class' => 'Modules\\ModuleRelease2\\Database\\Seeders\\DatabaseSeeder',
                '--database' => $connection,
                '--force' => true,
            ]);
        }

        // Update autoload in composer.json
        if (!app()->environment('main')) {
            $this->call('module:modulerelease2:update-autoload');
        }

        $this->info('✅ Module Release 2 installed successfully!');
    }

    /**
     * Get the value of an environment variable from the .env file.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    protected function getEnvValue(string $key, $default = null)
    {
        $envPath = base_path('.env');
        if (!file_exists($envPath)) {
            return $default;
        }

        $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos($line, '=') !== false) {
                [$envKey, $envValue] = explode('=', $line, 2);
                $envKey = trim($envKey);
                $envValue = trim($envValue, " \t\n\r\0\x0B\"'");

                if ($envKey === $key) {
                    return $envValue;
                }
            }
        }

        return $default;
    }   
}