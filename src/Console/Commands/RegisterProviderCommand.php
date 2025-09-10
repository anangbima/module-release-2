<?php 

namespace Modules\ModuleRelease2\Console\Commands;

use Illuminate\Console\Command;

class RegisterProviderCommand extends Command
{
    /**
     * The console command description.
     *
     * @var string
     */
    protected $signature;
    protected $description = 'Register the Module Release 2 service provider';

    public function __construct()
    {
        // Set the command signature using the module prefix from the configuration
        // This allows the command to be namespaced under the module's prefix
        $this->signature = 'module:modulerelease2:register-provider';

        parent::__construct();
    }

    /**
     * Execute the console command.
     * 
     * This command registers the service provider for Test Module 2
     * by adding it to the application's configuration.
     * 
     * @return void
     */
    public function handle()
    {
        $provider = 'Modules\ModuleRelease2\Providers\ModuleServiceProvider';
        $configPath = config_path('modules.php');

        if (!file_exists($configPath)) {
            $this->info('🛠 Creating config/modules.php ...');
            file_put_contents($configPath, "<?php\n\nreturn [\n    'providers' => [\n    ],\n];\n");
            $this->newLine();
        }

        $config = include $configPath;

        if (!is_array($config) || !isset($config['providers'])) {
            $config = ['providers' => []];
        }

        if (!in_array($provider, $config['providers'])) {
            $config['providers'][] = $provider;

            file_put_contents($configPath, '<?php return ' . var_export($config, true) . ';');
            $this->info('✅ Service provider registered to config/modules.php');
        } else {
            $this->info('ℹ️  Service provider already registered.');
        }

        $this->callSilent('config:clear');
        $this->info('🧹 Configuration cache cleared.');
        $this->newLine();
        
        return 0;
    }

}