<?php

namespace Modules\ModuleRelease2\Console\Commands;

use Illuminate\Console\Command;

class AddEnvCommand extends Command
{
    protected $signature;
    protected $description = 'Add environment variables for Module Release 2';

    public function __construct()
    {
        $this->signature = 'module:modulerelease2:add-env';
        parent::__construct();
    }
    
    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $envVariables = [
            'MODULE_RELEASE_2_ENABLED' => 'true',
            'MODULE_RELEASE_2_NAME' => 'Module Release 2',
            'MODULE_RELEASE_2_SLUG' => 'module-release-2',
            'MODULE_RELEASE_2_PREFIX' => 'module-release-2',
            'MODULE_RELEASE_2_DB_DRIVER' => 'mysql',
            'MODULE_RELEASE_2_DB_CONNECTION' => 'module_release_2',
            'MODULE_RELEASE_2_DB_HOST' => '127.0.0.1',
            'MODULE_RELEASE_2_DB_PORT' => '3306',
            'MODULE_RELEASE_2_DB_DATABASE' => 'module_release_2',
            'MODULE_RELEASE_2_DB_USERNAME' => 'root',
            'MODULE_RELEASE_2_DB_PASSWORD' => '',
            'MODULE_RELEASE_2_DOMAIN' => 'modulerelease2.test',
            'MODULE_RELEASE_2_SESSION_DRIVER' => 'database',
            'MODULE_RELEASE_2_SESSION_CONNECTION' => 'module_release_2',
            'MODULE_RELEASE_2_SESSION_TABLE' => 'module_release_2_sessions',
            'MODULE_RELEASE_2_SESSION_COOKIE' => 'module_release_2_session',
            ''
        ];

        $envPath = base_path('.env');

        if (!file_exists($envPath)) {
            $this->error('.env file not found at: ' . $envPath);
            return 1;
        }

        $envContent = file_get_contents($envPath);

        foreach ($envVariables as $key => $value) {
            if (strpos($envContent, $key) === false) {
                // Escape nilai yang mengandung spasi atau karakter khusus
                if (preg_match('/\s/', $value)) {
                    $value = '"' . addslashes($value) . '"';
                }

                $envContent .= "\n{$key}={$value}";
                $this->line("Added {$key} to .env");
            } else {
                $this->info("{$key} already exists in .env");
            }
        }

        file_put_contents($envPath, $envContent);

        return 0;
    }
}