<?php
final class ApiRequest
{
    public static function capture(): self
    {
        $route = trim((string) ($_GET['url'] ?? ''), '/');
        $path = '/' . ltrim(substr($route, 3), '/'); // buang prefix "api"

        $query = $_GET;
        unset($query['url']);

        $headers = function_exists('getallheaders') ? getallheaders() : [];
        $rawBody = (string) file_get_contents('php://input');

        return new self($_SERVER['REQUEST_METHOD'] ?? 'GET', $path, $query, $headers, $rawBody);
    }

    public function json(): array
    {
        if ($this->decodedJson !== null) {
            return $this->decodedJson;
        }

        if ($this->rawBody === '') {
            return $this->decodedJson = [];
        }

        $contentType = strtolower((string) $this->header('content-type', ''));
        if (!str_contains($contentType, 'application/json')
            && !str_contains($contentType, '+json')) {
            ApiResponse::unsupportedMediaType();
        }

        try {
            $decoded = json_decode($this->rawBody, true, 64, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            ApiResponse::badRequest('Body JSON tidak valid: ' . $e->getMessage());
        }

        if (!is_array($decoded)) {
            ApiResponse::badRequest('Body JSON harus berupa objek.');
        }

        return $this->decodedJson = $decoded;
    }

    public function enforceJsonContentType(): void
    {
        if (!in_array($this->method, ['POST', 'PUT', 'PATCH'], true)) {
            return;
        }

        // Aksi seperti POST /items/{id}/restore tidak punya body.
        if ($this->rawBody === '' || (int) ($_SERVER['CONTENT_LENGTH'] ?? 0) === 0) {
            return;
        }

        $contentType = strtolower((string) $this->header('content-type', ''));
        if (!str_contains($contentType, 'application/json')
            && !str_contains($contentType, '+json')) {
            ApiResponse::unsupportedMediaType();
        }
    }
}