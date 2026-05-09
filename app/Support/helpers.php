<?php

declare(strict_types=1);

function app(): App\Core\Application
{
    return App\Core\Application::getInstance();
}

function config(string $key, mixed $default = null): mixed
{
    return app()->config($key, $default);
}

function asset(string $path): string
{
    $path = '/' . ltrim($path, '/');
    $baseUrl = rtrim((string)config('base_url', ''), '/');

    return $baseUrl . $path;
}

function url(string $path = '/'): string
{
    $path = '/' . ltrim($path, '/');
    $baseUrl = rtrim((string)config('base_url', ''), '/');

    if ($path === '//') {
        $path = '/';
    }

    return $baseUrl . $path;
}
