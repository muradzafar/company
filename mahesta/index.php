<?php
require __DIR__ . '/core/Database.php';
require __DIR__ . '/core/Auth.php';

spl_autoload_register(function ($class) {
    $paths = [__DIR__ . '/controllers', __DIR__ . '/models'];
    foreach ($paths as $path) {
        $file = $path . '/' . $class . '.php';
        if (file_exists($file)) {
            require $file;
            return;
        }
    }
});

$uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$base = trim((require __DIR__ . '/config.php')['base_url'], '/');
if ($base && strpos($uri, $base) === 0) {
    $uri = ltrim(substr($uri, strlen($base)), '/');
}

$routes = [
    '' => [HomeController::class, 'index'],
    'admin' => [AdminController::class, 'index'],
    'admin/login' => [AdminController::class, 'login'],
    'admin/logout' => [AdminController::class, 'logout'],
    'admin/products' => [ProductController::class, 'index'],
    'admin/products/create' => [ProductController::class, 'create'],
];

if (isset($routes[$uri])) {
    [$controller, $method] = $routes[$uri];
    (new $controller)->$method();
} else {
    http_response_code(404);
    echo '404 Not Found';
}
