<?php

class Logger {
    private static string $logDir = ROOT_PATH . '/logs/errors';

    public static function error(string $message, array $context = []): void {
        // Jika folder logs/errors belum ada, buat otomatis
        if (!is_dir(self::$logDir)) {
            mkdir(self::$logDir, 0777, true);
        }

        // Simpan error ke file JSON berdasarkan tanggal dan waktu
        file_put_contents(
            self::$logDir . '/error-' . date('YmdHis') . '.json',
            json_encode([
                'timestamp' => date('Y-m-d H:i:s'),
                'message'   => $message,
                'context'   => $context
            ], JSON_PRETTY_PRINT)
        );
    }
}