<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$request = Illuminate\Http\Request::create('/api/push/public-key', 'GET');
try {
    $route = app('router')->getRoutes()->match($request);
    echo $route->uri();
} catch (Throwable $e) {
    echo get_class($e) . ':' . $e->getMessage();
}
