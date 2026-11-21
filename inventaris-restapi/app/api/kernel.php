<?php
ApiCors::handle();

$request = ApiRequest::capture();
$request->validateBodySize();
$request->enforceJsonContentType();

set_exception_handler(static function (Throwable $e): void {
    Logger::error('Unhandled API exception', [
        'type' => get_class($e), 'message' => $e->getMessage(),
        'file' => $e->getFile(), 'line' => $e->getLine(),
    ]);

    if (headers_sent()) {
        return;
    }

    if ($e instanceof ApiException) {
        ApiResponse::error($e->status(), $e->getMessage(), $e->details(), $e->headers());
    }

    $details = APP_DEBUG ? [
        'type' => get_class($e), 'message' => $e->getMessage(),
        'file' => $e->getFile(), 'line' => $e->getLine(),
    ] : [];

    ApiResponse::error(500, 'Terjadi kesalahan pada server.', $details);
});

$startedAt = microtime(true);

register_shutdown_function(static function () use ($startedAt, $request): void {
    if (!API_LOG_REQUESTS) {
        return;
    }

    try {
        $context = $GLOBALS['api_context'] ?? [];
        $status = (int) (http_response_code() ?: 200);

        (new ApiRequestLog())->record(
            $context['user']['id'] ?? null,
            $context['token_id'] ?? null,
            $request->method(),
            $request->path(),
            $status,
            (int) round((microtime(true) - $startedAt) * 1000),
            $request->ip()
        );
    } catch (Throwable $e) {
        Logger::error('API request logging failed', ['error' => $e->getMessage()]);
    }
});

$router = new ApiRouter();
require __DIR__ . '/routes.php';
$router->dispatch($request->method(), $request->path());