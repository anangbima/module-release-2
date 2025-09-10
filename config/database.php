<?php 

return [
    // Database connection settings
    'database_connection' => env('DESA_MODULE_TEMPLATE_DB_CONNECTION', env('DB_CONNECTION', 'mysql')),

    // Permission table prefix
    // This prefix is used to differentiate permissions for this module
    'permission_table_prefix' => 'desa_module_template_',

    // Migration settings    
    'connection' => [
        'driver' => env('DESA_MODULE_TEMPLATE_DB_DRIVER', 'mysql'),
        'host' => env('DESA_MODULE_TEMPLATE_DB_HOST', '127.0.0.1'),
        'port' => env('DESA_MODULE_TEMPLATE_DB_PORT', '3306'),
        'database' => env('DESA_MODULE_TEMPLATE_DB_DATABASE', 'desa_module_template'),
        'username' => env('DESA_MODULE_TEMPLATE_DB_USERNAME', 'root'),
        'password' => env('DESA_MODULE_TEMPLATE_DB_PASSWORD', ''),
        'charset' => env('DESA_MODULE_TEMPLATE_DB_CHARSET', 'utf8mb4'),
        'collation' => env('DESA_MODULE_TEMPLATE_DB_COLLATION', 'utf8mb4_unicode_ci'),
        'prefix' => env('DESA_MODULE_TEMPLATE_DB_PREFIX', ''),
        'strict' => env('DESA_MODULE_TEMPLATE_DB_STRICT', true), 
        'engine' => env('DESA_MODULE_TEMPLATE_DB_ENGINE', null),
    ],
];
