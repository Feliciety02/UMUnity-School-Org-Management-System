<?php

declare(strict_types=1);

namespace App\Core;

final class Router
{
    private array $routes = [
        'GET' => [],
        'POST' => [],
    ];

    public function __construct(private readonly Application $app)
    {
    }

    public function get(string $path, callable|array $handler): void
    {
        $this->routes['GET'][$this->normalize($path)] = $handler;
    }

    public function post(string $path, callable|array $handler): void
    {
        $this->routes['POST'][$this->normalize($path)] = $handler;
    }

    public function dispatch(string $method, string $path): void
    {
        $method = strtoupper($method);
        $path = $this->normalize($path);
        $handler = $this->routes[$method][$path] ?? null;

        if ($handler === null) {
            http_response_code(404);
            echo View::make($this->app->basePath('app/Views/errors/404.php'), [
                'title' => 'Page Not Found',
            ]);
            return;
        }

        if (is_array($handler) && is_string($handler[0])) {
            $controller = new $handler[0]();
            $action = $handler[1];
            $controller->{$action}();
            return;
        }

        $handler();
    }

    private function normalize(string $path): string
    {
        if ($path === '') {
            return '/';
        }

        $path = '/' . trim($path, '/');
        return $path === '//' ? '/' : $path;
    }
}
