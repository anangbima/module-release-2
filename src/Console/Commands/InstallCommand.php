<?php 

namespace Modules\DesaModuleTemplate\Console\Commands;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    /**
     * The console command description.
     *
     * @var string
     */
    protected $signature;
    protected $description = 'Install Desa Module Template';

    public function __construct()
    {
        // Set the command signature using the module prefix from the configuration
        // This allows the command to be namespaced under the module's prefix

        $this->signature = 'module:desamoduletemplate:install
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
        $this->info('Installing Desa Module Template ...');
        $this->newLine();
    
        // Register the service provider
        $this->call('module:desamoduletemplate:register-provider');

        // Add environment variables
        $this->call('module:desamoduletemplate:add-env');
        
        $connection = $this->getEnvValue('DESA_MODULE_TEMPLATE_DB_CONNECTION');
        
        config([
            'database.connections.' . $connection => [
                'driver' => $this->getEnvValue('DESA_MODULE_TEMPLATE_DB_DRIVER'),
                'host' => $this->getEnvValue('DESA_MODULE_TEMPLATE_DB_HOST'),
                'port' => $this->getEnvValue('DESA_MODULE_TEMPLATE_DB_PORT'),
                'database' => $this->getEnvValue('DESA_MODULE_TEMPLATE_DB_DATABASE'),
                'username' => $this->getEnvValue('DESA_MODULE_TEMPLATE_DB_USERNAME'),
                'password' => $this->getEnvValue('DESA_MODULE_TEMPLATE_DB_PASSWORD'),
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
            ],
            'database.default' => $connection,
        ]);

        if (!$connection) {
            $this->error('❌ DESA_MODULE_TEMPLATE_DB_CONNECTION not set in .env');
            return Command::FAILURE;
        }
            
        $this->newLine();
        $this->info("Using DB connection: $connection");

        if (app()->environment('test')) {
            $migrationPath = 'modules/desa-module-template/src/Database/Migrations';
            $realMigrationPath = base_path($migrationPath);
        } else {
            $realMigrationPath = realpath('vendor/modules/desa-module-template/src/Database/Migrations');
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
                '--class' => 'Modules\\DesaModuleTemplate\\Database\\Seeders\\DatabaseSeeder',
                '--database' => $connection,
                '--force' => true,
            ]);
        }

        // Update autoload in composer.json
        if (!app()->environment('main')) {
            $this->call('module:desamoduletemplate:update-autoload');
        }

        $this->info('✅ Desa Module Template installed successfully!');
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