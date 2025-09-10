<?php

namespace Modules\DesaModuleTemplate\Providers\Concerns;

trait LoadAuthConfig
{
    /**
     * Load the module's authentication configuration.
     * 
     * @return void
     */
    protected function loadAuthConfig()
    {
        // Load the authentication configuration file from the module
        $config = require __DIR__ . '/../../../config/auth.php';

        // Merge the module's authentication configuration into the application's config
        if (isset($config['guards'])) {
            config([
                'auth.guards' => array_merge(config('auth.guards', []), $config['guards']),
            ]);
        }
        
        // Merge the module's user providers into the application's config
        if (isset($config['providers'])) {
            config([
                'auth.providers' => array_merge(config('auth.providers', []), $config['providers']),
            ]);
        }

        // Merge the password
        if (isset($config['passwords'])) {
            config([
                'auth.passwords' => array_merge(config('auth.passwords', []), $config['passwords']),
            ]);
        }

        // Apply OTP settings if present
        if (isset($config['otp'])) {
            config([
                'auth.otp' => $config['otp'],
            ]);
        }
    }
}
