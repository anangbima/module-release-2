<?php

use Modules\ModuleRelease2\Models\User;

return [
    // default guard for this module
    'default' => 'module_release_2_web',

    // Custom guard for this module
    'guards' => [
        'module_release_2_web' => [
            'driver' => 'session',
            'provider' => 'module_release_2_users',
        ],

        'module_release_2_api' => [
            'driver' => 'jwt',
            'provider' => 'module_release_2_users',
        ],
    ],

    // Custom user provider for this module
    'providers' => [
        'module_release_2_users' => [
            'driver' => 'eloquent',
            'model' => User::class
        ],
    ],

    'passwords' => [
        'module_release_2_users' => [
            'provider' => 'module_release_2_users',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60    
        ] 
    ],

    /*
    |--------------------------------------------------------------------------
    | OTP Settings
    |--------------------------------------------------------------------------
    | Control whether OTP is required in this module. You can enable it globally
    | or restrict it to certain roles. Developers can override via env or
    | publish config.
    */
    'otp' => [
        'enabled' => true, // optionally using env
        'roles'   => ['user'], // roles that must use OTP
        'expiry'  => 5, // minutes // optioanally using env
    ]
];