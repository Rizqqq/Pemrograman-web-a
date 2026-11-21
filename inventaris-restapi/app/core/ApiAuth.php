<?php
class ApiAuth
{
    public static function authenticate(): array
    {
        $token = self::bearerToken();
        if ($token === null) {
            self::fail(401, 'Token API tidak ditemukan. Kirim header: Authorization: Bearer <token>.');
        }

        $tokenModel = new ApiToken();
        $row = $tokenModel->findUserByToken($token);

        if (!$row) {
            self::fail(401, 'Token API tidak valid, kedaluwarsa, atau sudah dicabut.');
        }

        $tokenModel->touch((int) $row['token_id']);

        $abilities = null;
        if ($row['abilities'] !== null && $row['abilities'] !== '') {
            $decoded = json_decode((string) $row['abilities'], true);
            $abilities = is_array($decoded)
                ? array_values(array_filter($decoded, 'is_string'))
                : [];
        }

        $context = [
            'token_id'   => (int) $row['token_id'],
            'token_name' => (string) $row['token_name'],
            'abilities'  => $abilities,
            'expires_at' => $row['expires_at'],
            'user'       => [
                'id'        => (int) $row['id'],
                'username'  => (string) $row['username'],
                'full_name' => $row['full_name'],
                'role'      => (string) $row['role'],
            ],
        ];

        $GLOBALS['api_context'] = $context;
        return $context;
    }

    public static function can(string $permission): bool
    {
        $context = $GLOBALS['api_context'] ?? null;
        if (!is_array($context) || empty($context['user']['role'])) {
            return false;
        }

        if (!Middleware::roleCan((string) $context['user']['role'], $permission)) {
            return false;
        }

        $abilities = $context['abilities'] ?? null;
        if ($abilities === null) {
            return true; // semua izin role
        }

        foreach ($abilities as $ability) {
            if ($ability === '*' || $ability === $permission) {
                return true;
            }
            if (str_ends_with($ability, '.*')
                && str_starts_with($permission, substr($ability, 0, -1))) {
                return true;
            }
        }

        return false;
    }
}