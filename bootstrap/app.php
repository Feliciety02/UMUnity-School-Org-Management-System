<?php

declare(strict_types=1);

require_once __DIR__ . '/../database/config.php';
require_once __DIR__ . '/../database/functions.php';
require_once __DIR__ . '/../app/Support/helpers.php';

spl_autoload_register(static function (string $class): void {
    $prefix = 'App\\';

    if (!str_starts_with($class, $prefix)) {
        return;
    }

    $relativeClass = substr($class, strlen($prefix));
    $file = __DIR__ . '/../app/' . str_replace('\\', '/', $relativeClass) . '.php';

    if (is_file($file)) {
        require_once $file;
    }
});

$config = require __DIR__ . '/../config/app.php';

$app = new App\Core\Application(dirname(__DIR__), $config);

$app->router()->get('/', [App\Controllers\HomeController::class, 'index']);
$app->router()->get('/login', [App\Controllers\AuthController::class, 'showLogin']);
$app->router()->post('/login', [App\Controllers\AuthController::class, 'login']);
$app->router()->get('/register', [App\Controllers\AuthController::class, 'showRegister']);
$app->router()->post('/register', [App\Controllers\AuthController::class, 'register']);
$app->router()->post('/logout', [App\Controllers\AuthController::class, 'logout']);
$app->router()->get('/logout', [App\Controllers\AuthController::class, 'logout']);
$app->router()->get('/dashboard', [App\Controllers\DashboardController::class, 'index']);
$app->router()->get('/dashboard/admin', [App\Controllers\DashboardController::class, 'admin']);
$app->router()->get('/dashboard/leader', [App\Controllers\DashboardController::class, 'leader']);
$app->router()->get('/dashboard/student', [App\Controllers\DashboardController::class, 'student']);

return $app;
