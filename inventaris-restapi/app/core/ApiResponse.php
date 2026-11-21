<?php
final class ApiResponse
{
    public static function json($data, int $status = 200, array $headers = []): void
    {
        $body = self::encode($data);

        if (isset($headers['ETag']) && self::etagMatches((string) $headers['ETag'])) {
            http_response_code(304);
            header('ETag: ' . $headers['ETag']);
            header('Cache-Control: private, max-age=0, must-revalidate');
            exit;
        }

        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        header('X-Content-Type-Options: nosniff');
        header('Cache-Control: no-store');
        header('Content-Length: ' . strlen($body));

        foreach ($headers as $name => $value) {
            header($name . ': ' . $value);
        }

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'HEAD') {
            echo $body;
        }

        exit;
    }

    public static function payload(
        $data = null, int $status = 200,
        array $meta = [], array $links = [], ?string $message = null
    ): array {
        $payload = ['success' => $status < 400];
        if ($message !== null) { $payload['message'] = $message; }
        if ($data !== null) { $payload['data'] = $data; }
        if ($meta !== []) { $payload['meta'] = $meta; }
        if ($links !== []) { $payload['links'] = $links; }
        if (defined('API_REQUEST_ID')) { $payload['request_id'] = API_REQUEST_ID; }
        return $payload;
    }

    public static function hash($data): string
    {
        return '"' . hash('sha256', self::encode($data)) . '"';
    }

    public static function error(int $status, string $message, array $errors = [], array $headers = []): void
    {
        $payload = ['success' => false, 'message' => $message];
        if ($errors !== []) { $payload['errors'] = $errors; }
        if (defined('API_REQUEST_ID')) { $payload['request_id'] = API_REQUEST_ID; }
        self::json($payload, $status, $headers);
    }
}