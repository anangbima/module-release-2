<?php 

return [
    // Database connection settings
    'database_connection' => env('MODULE_RELEASE_2_DB_CONNECTION', env('DB_CONNECTION', 'mysql')),

    // Permission table prefix
    // This prefix is used to differentiate permissions for this module
    'permission_table_prefix' => 'module_release_2_',

    // Migration settings    
    'connection' => [
        'driver' => env('MODULE_RELEASE_2_DB_DRIVER', 'mysql'),
        'host' => env('MODULE_RELEASE_2_DB_HOST', '127.0.0.1'),
        'port' => env('MODULE_RELEASE_2_DB_PORT', '3306'),
        'database' => env('MODULE_RELEASE_2_DB_DATABASE', 'module_release_2'),
        'username' => env('MODULE_RELEASE_2_DB_USERNAME', 'root'),
        'password' => env('MODULE_RELEASE_2_DB_PASSWORD', ''),
        'charset' => env('MODULE_RELEASE_2_DB_CHARSET', 'utf8mb4'),
        'collation' => env('MODULE_RELEASE_2_DB_COLLATION', 'utf8mb4_unicode_ci'),
        'prefix' => env('MODULE_RELEASE_2_DB_PREFIX', ''),
        'strict' => env('MODULE_RELEASE_2_DB_STRICT', true), 
        'engine' => env('MODULE_RELEASE_2_DB_ENGINE', null),
    ],
];
