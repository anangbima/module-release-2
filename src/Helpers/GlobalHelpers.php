<?php

use Illuminate\Support\Facades\Auth;
use Modules\ModuleRelease2\Helpers\ModuleMeta;

if (!function_exists('module_release_2_meta')) {
    function module_release_2_meta(string $key): ?string {
        return ModuleMeta::get($key);
    }
}

if(!function_exists(('module_release_2_auth_user'))) {
    function module_release_2_auth_user()
    {
       return Auth::guard(module_release_2_meta('snake') . '_web')->user();
    }
}

if (!function_exists('module_release_2_fileName')) {
    function module_release_2_fileName(string $base, string $extension): string
    {
        $timestamp = now()->format('Ymd_His');
        return module_release_2_meta('snake') . "_{$base}_{$timestamp}.{$extension}";
    }
}