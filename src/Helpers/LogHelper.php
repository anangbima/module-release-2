<?php

use Illuminate\Support\Str;

if (!function_exists('module_release_2_format_action')) {
    function module_release_2_format_action(string $action): string
    {
        return Str::of($action)
            ->replace('_', ' ')
            ->title();
    }
}
if (!function_exists('module_release_2_action_color')) {
    function module_release_2_action_color(string $action): string
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
