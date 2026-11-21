USE db_inventory;

-- 1. Buat tabel api_tokens (sudah lengkap dengan abilities, expires_at, revoked_at)
CREATE TABLE IF NOT EXISTS api_tokens (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    user_id      INT NOT NULL,
    token_hash   CHAR(64) NOT NULL UNIQUE,
    name         VARCHAR(100) NOT NULL DEFAULT 'default',
    abilities    TEXT NULL COMMENT 'JSON array; NULL = all role permissions',
    expires_at   DATETIME NULL,
    revoked_at   DATETIME NULL,
    last_used_at DATETIME NULL,
    created_at   TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_api_tokens_user (user_id),
    INDEX idx_api_tokens_expires (expires_at),
    CONSTRAINT fk_api_tokens_user FOREIGN KEY (user_id)
        REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Buat tabel api_idempotency
CREATE TABLE IF NOT EXISTS api_idempotency (
    id               INT AUTO_INCREMENT PRIMARY KEY,
    token_id         INT NOT NULL,
    idem_key         VARCHAR(100) NOT NULL,
    request_hash     CHAR(64) NOT NULL,
    response_code    SMALLINT UNSIGNED NOT NULL,
    response_body    MEDIUMTEXT NOT NULL,
    response_headers TEXT NULL,
    created_at       TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_api_idempotency (token_id, idem_key),
    INDEX idx_api_idempotency_created (created_at),
    CONSTRAINT fk_api_idempotency_token FOREIGN KEY (token_id)
        REFERENCES api_tokens(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Buat tabel api_request_logs
CREATE TABLE IF NOT EXISTS api_request_logs (
    id          BIGINT AUTO_INCREMENT PRIMARY KEY,
    user_id     INT NULL,
    token_id    INT NULL,
    method      VARCHAR(10) NOT NULL,
    path        VARCHAR(255) NOT NULL,
    status_code SMALLINT UNSIGNED NOT NULL,
    duration_ms INT UNSIGNED NOT NULL DEFAULT 0,
    ip_address  VARCHAR(45) NULL,
    created_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_api_logs_created (created_at),
    INDEX idx_api_logs_status (status_code),
    INDEX idx_api_logs_token (token_id),
    CONSTRAINT fk_api_logs_user FOREIGN KEY (user_id)
        REFERENCES users(id) ON DELETE SET NULL,
    CONSTRAINT fk_api_logs_token FOREIGN KEY (token_id)
        REFERENCES api_tokens(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;