<?php
final class ApiCors
{
    public static function handle(): void
    {
        self::headers();

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'OPTIONS') {
            http_response_code(204);
            header('Content-Length: 0');
            exit;
        }
    }

    private static function headers(): void
    {
        $origin = trim((string) ($_SERVER['HTTP_ORIGIN'] ?? ''));
        $configured = trim((string) API_ALLOWED_ORIGINS);
        $allowed = array_values(array_filter(array_map('trim', explode(',', $configured))));

        if ($configured === '*' || in_array('*', $allowed, true)) {
            header('Access-Control-Allow-Origin: *');
        } elseif ($origin !== '' && in_array($origin, $allowed, true)) {
            header('Access-Control-Allow-Origin: ' . $origin);
        }

        header('Vary: Origin');
        header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Authorization, Content-Type, Accept, If-None-Match, Idempotency-Key, X-Requested-With');
        header('Access-Control-Expose-Headers: ETag, Location, Content-Length, Retry-After, X-RateLimit-Limit, X-RateLimit-Remaining, X-RateLimit-Reset, X-Request-Id');
        header('Access-Control-Max-Age: 86400');

        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: DENY');
        header('Referrer-Policy: no-referrer');

        if (is_https()) {
            header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
        }
    }
}