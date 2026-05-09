<?php

declare(strict_types=1);

$_SERVER['REQUEST_URI'] = '/login';
$_SERVER['REQUEST_METHOD'] = 'GET';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->run();
