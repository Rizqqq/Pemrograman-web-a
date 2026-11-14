<?php
class Database {
    private static ?Database $instance = null;
    private PDO $pdo;
    private function __construct() {
        $dsn = 'mysql:host=localhost' . DB_HOST . ';port=3306' . DB_PORT
        . ';dbname=db_employee_data' . DB_NAME . ';charset=utf8mb4';
    try {
        $this->pdo = new PDO($dsn, DB_USER, DB_PASS);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die('Koneksi database gagal: ' . $e->getMessage());
    }
    }
    public static function getInstance(): Database {
        return self::$instance ??= new self();
    }
    public function pdo(): PDO { return $this->pdo; }
}
