<?php
class ApiToken extends Model
{
    public function findUserByToken(string $plainToken): ?array
    {
        $sql = "SELECT t.id AS token_id, t.name AS token_name, t.abilities,
                       t.expires_at, t.last_used_at,
                       u.id, u.username, u.full_name, u.role
                FROM api_tokens t
                INNER JOIN users u ON u.id = t.user_id
                WHERE t.token_hash = ?
                  AND t.revoked_at IS NULL
                  AND (t.expires_at IS NULL OR t.expires_at > NOW())
                  AND u.is_active = 1
                LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([Security::hashToken($plainToken)]);
        return $stmt->fetch() ?: null;
    }

    public function issue(
        int $userId, string $name = 'default',
        ?array $abilities = null, ?int $expiresInDays = null
    ): string {
        $plain = Security::generateToken(); // inv_ + 24 byte acak

        $expiresAt = $expiresInDays
            ? date('Y-m-d H:i:s', time() + ($expiresInDays * 86400))
            : null;

        $stmt = $this->db->prepare(
            "INSERT INTO api_tokens (user_id, token_hash, name, abilities, expires_at)
             VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->execute([
            $userId,
            Security::hashToken($plain),
            mb_substr($name, 0, 100),
            $abilities === null ? null : json_encode(array_values($abilities)),
            $expiresAt,
        ]);

        return $plain;
    }
}