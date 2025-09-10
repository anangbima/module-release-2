<?php

use Illuminate\Support\Facades\Auth;
use Modules\DesaModuleTemplate\Helpers\ModuleMeta;

if (!function_exists('desa_module_template_meta')) {
    function desa_module_template_meta(string $key): ?string {
        return ModuleMeta::get($key);
    }
}

if(!function_exists(('desa_module_template_auth_user'))) {
    function desa_module_template_auth_user()
    {
       return Auth::guard(desa_module_template_meta('snake') . '_web')->user();
    }
}

if (!function_exists('desa_module_template_fileName')) {
    function desa_module_template_fileName(string $base, string $extension): string
    {
        $timestamp = now()->format('Ymd_His');
        return desa_module_template_meta('snake') . "_{$base}_{$timestamp}.{$extension}";
    }
}