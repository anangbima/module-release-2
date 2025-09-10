<?php 

namespace Modules\DesaModuleTemplate\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateCommand extends Command
{
    /**
     * The console command description.
     *
     * @var string
     */
    protected $signature;
    protected $description = 'Run migrations for Desa Module Template';

    public function __construct()
    {
        // Set the command signature using the module prefix from the configuration
        // This allows the command to be namespaced under the module's prefix
        $this->signature = 'module:desamoduletemplate:migrate
                            {--fresh : Drop all tables and re-run all migrations}
                            {--refresh : Reset and re-run all migrations}
                            {--seed : Seed the module after migration}';

        parent::__construct();
    }

    /**
     * Execute the console command.
     * 
     * @return Command
     */
    public function handle()
    {
        // Check if the application is in the test environment
        if (app()->environment('production')){
            $this->error('Migrations cannot be run in the test environment.');
            return 1; // Exit with error code
        }

        $connection = config('desa_module_template.database.database_connection', 'desa_module_template');
        $database = config('desa_module_template.database.connection.database', 'desa_module_template');
        $defultConnection = config('database.default', 'mysql');

        // Check if the connection is set to the default connection
        $this->info("Running migrations for connection: {$connection} on database: {$database}");
        
        $exists = DB::connection($defultConnection)
                    ->select("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?", [$database]);

        if (empty($exists)) {
            DB::connection($defultConnection)->statement("CREATE DATABASE `$database` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $this->info("✅ Database `$database` created.");
        } else {
            $this->info("Database {$database} exists. Proceeding with migrations.");
        }

        $migrationPath = 'modules/desa-module-template/src/Database/Migrations';
        $realMigrationPath = base_path('modules/desa-module-template/src/Database/Migrations');

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

        return Command::SUCCESS;
    }
}