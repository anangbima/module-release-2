<?php 

return [
    // Module name
    'name' => env('DESA_MODULE_TEMPLATE_NAME', 'Desa Module Template'),

    // Module slug
    'slug' => env('DESA_MODULE_TEMPLATE_SLUG', 'desa-module-template'),

    // Setting enabled state
    'enabled' => env('DESA_MODULE_TEMPLATE_ENABLED', true),

    // route prefix 
    'route_prefix' => env('DESA_MODULE_TEMPLATE_PREFIX', 'desa-module-template'),

    // view namespace
    'view_namespace' => env('DESA_MODULE_TEMPLATE_VIEW', 'desa-module-template'),

    // Module domain
    'domain' => env('DESA_MODULE_TEMPLATE_DOMAIN', 'desamoduletemplate.test'),
];