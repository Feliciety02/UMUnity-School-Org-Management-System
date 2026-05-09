<?php

declare(strict_types=1);

namespace App\Core;

final class Application
{
    private static ?self $instance = null;

    private Router $router;
    private Session $session;

    public function __construct(
        private readonly string $basePath,
        private readonly array $config
    ) {
        self::$instance = $this;
        $this->session = new Session($config['session_key'] ?? 'app');
        $this->router = new Router($this);
    }

    public static function getInstance(): self
    {
        if (!self::$instance) {
            throw new \RuntimeException('Application has not been bootstrapped.');
        }

        return self::$instance;
    }

    public function basePath(string $path = ''): string
    {
        $path = ltrim($path, '/\\');
        return $path === '' ? $this->basePath : $this->basePath . DIRECTORY_SEPARATOR . $path;
    }

    public function config(string $key, mixed $default = null): mixed
    {
        return $this->config[$key] ?? $default;
    }

    public function router(): Router
    {
        return $this->router;
    }

    public function session(): Session
    {
        return $this->session;
    }

    public function db(): \mysqli
    {
        global $conn;
        return $conn;
    }

    public function run(): void
    {
        $this->router->dispatch(
            $_SERVER['REQUEST_METHOD'] ?? 'GET',
            parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/'
        );
    }
}
