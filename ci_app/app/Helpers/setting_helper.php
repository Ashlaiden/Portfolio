<?php

if (!function_exists('admin_url')) {
    function admin_url(string $uri = ''): string
    {
        $settings = service('settings');
        $prefix = $settings->get('admin_prefix') ?? env('ADMIN_DEFAULT_PREFIX');

        return base_url(trim($prefix . '/' . ltrim($uri, '/'), '/'));
    }
}