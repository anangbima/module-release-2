<?php

use Illuminate\Support\Str;

if (!function_exists('desa_module_template_format_action')) {
    function desa_module_template_format_action(string $action): string
    {
        return Str::of($action)
            ->replace('_', ' ')
            ->title();
    }
}
if (!function_exists('desa_module_template_action_color')) {
    function desa_module_template_action_color(string $action): string
    {
        return match (true) {
            Str::startsWith($action, 'create') => 'success-light',
            Str::startsWith($action, 'update') => 'warning-light',
            Str::startsWith($action, 'delete') => 'danger-light',
            Str::contains($action, 'notification') => 'violet-light',
            Str::contains($action, ['login', 'logout', 'register', 'password', 'verify', 'resend']) => 'info-light',
            default => 'secondary-light',
        };
    }
}
