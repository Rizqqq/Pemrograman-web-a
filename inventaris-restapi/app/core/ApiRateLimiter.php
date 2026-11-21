<?php
final class ApiRateLimiter
{
    public static function consume(string $key, int $limit, int $window): array
    {
        $now = time();
        if ($limit <= 0 || $window <= 0) {
            return ['allowed' => true, 'limit' => 0, 'remaining' => 0, 'reset' => $now];
        }

        $directory = self::directory();
        if (!is_dir($directory) && !mkdir($directory, 0775, true) && !is_dir($directory)) {
            Logger::error('Rate limit directory is not writable', ['directory' => $directory]);
            return ['allowed' => true, 'limit' => $limit, 'remaining' => $limit, 'reset' => $now + $window];
        }

        $file = $directory . '/' . hash('sha256', $key) . '.json';
        $handle = @fopen($file, 'c+');
        if ($handle === false) {
            Logger::error('Rate limit file cannot be opened', ['file' => $file]);
            return ['allowed' => true, 'limit' => $limit, 'remaining' => $limit, 'reset' => $now + $window];
        }

        flock($handle, LOCK_EX);
        $state = json_decode((string) stream_get_contents($handle), true);
        if (!is_array($state) || !isset($state['start'], $state['count'])) {
            $state = ['start' => $now, 'count' => 0];
        }

        $start = (int) $state['start'];
        if (($now - $start) >= $window) {
            $start = $now;
            $state = ['start' => $start, 'count' => 0];
        }

        $state['count'] = (int) $state['count'] + 1;
        $allowed = $state['count'] <= $limit;
        $remaining = max(0, $limit - $state['count']);
        $reset = $start + $window;

        rewind($handle);
        ftruncate($handle, 0);
        fwrite($handle, json_encode($state));
        fflush($handle);
        flock($handle, LOCK_UN);
        fclose($handle);

        return compact('allowed', 'limit', 'remaining', 'reset');
    }

    public static function enforce(string $key, int $limit, int $window): array
    {
        $result = self::consume($key, $limit, $window);

        header('X-RateLimit-Limit: ' . $result['limit']);
        header('X-RateLimit-Remaining: ' . $result['remaining']);
        header('X-RateLimit-Reset: ' . $result['reset']);

        if (!$result['allowed']) {
            $retryAfter = max(1, $result['reset'] - time());
            ApiResponse::tooManyRequests(
                $retryAfter,
                'Batas ' . $result['limit'] . ' permintaan per ' . $window
                . ' detik terlampaui. Coba lagi dalam ' . $retryAfter . ' detik.'
            );
        }

        return $result;
    }
}