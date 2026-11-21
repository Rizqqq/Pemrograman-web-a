<?php
require_once __DIR__ . '/../app/config/config.php';
require_once __DIR__ . '/../app/core/helpers.php';

spl_autoload_register(function ($class) {
    foreach (['core', 'controllers', 'models'] as $dir) {
        $file = ROOT_PATH . '/app/' . $dir . '/' . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

ini_set('display_errors', APP_DEBUG ? '1' : '0');
ini_set('log_errors', '1');
error_reporting(E_ALL);

define('API_REQUEST_ID', bin2hex(random_bytes(8)));
header('X-Request-Id: ' . API_REQUEST_ID);

// Fallback ketika rewrite Apache tidak tersedia.
if (!isset($_GET['url'])) {
    $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
    $basePath = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
    if ($basePath !== '/' && $basePath !== '' && str_starts_with($uri, $basePath)) {
        $uri = substr($uri, strlen($basePath));
    }
    $_GET['url'] = trim((string) $uri, '/');
}

ApiCors::handle();

$route = strtolower(trim((string) ($_GET['url'] ?? ''), '/'));

if ($route === 'api' || str_starts_with($route, 'api/')) {
    require ROOT_PATH . '/app/api/kernel.php';
    exit;
}

if ($route === '') {
    ApiResponse::success([
        'name'       => APP_NAME,
        'version'    => API_VERSION,
        'entrypoint' => api_url('v1'),
        'health'     => api_url('v1/health'),
    ], 200, [], [], [], 'API server berjalan.');
}

ApiResponse::notFound('Endpoint tidak ditemukan. Mulai dari /api/v1.');