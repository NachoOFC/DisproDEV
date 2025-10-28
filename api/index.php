<?php

// Vercel entry point for Laravel
$basePath = dirname(__DIR__);

require_once $basePath . '/vendor/autoload.php';

$app = require_once $basePath . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);
