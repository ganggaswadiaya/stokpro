<?php
/**
 * @file config/database.php
 * @description Konfigurasi koneksi database MySQL menggunakan PDO
 * @package StokPro\Config
 */

namespace StokPro\Config;

/**
 * Class Database
 * Singleton pattern untuk koneksi PDO
 */
class Database
{
    // ── Properties ──────────────────────────────────
    private static ?Database $instance = null;
    private \PDO $pdo;

    private string $host     = 'localhost';
    private string $dbname   = 'stokpro';
    private string $username = 'root';
    private string $password = '';
    private string $charset  = 'utf8mb4';

    // ── Constructor (private — Singleton) ───────────
    private function __construct()
    {
        $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset={$this->charset}";
        $options = [
            \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        try {
            $this->pdo = new \PDO($dsn, $this->username, $this->password, $options);
        } catch (\PDOException $e) {
            die('<div style="font-family:system-ui;padding:20px;color:red"><b>Koneksi database gagal:</b><br>' . $e->getMessage() . '<br><br>Pastikan MySQL berjalan dan database <b>stokpro</b> sudah dibuat.</div>');
        }
    }

    /**
     * Ambil instance (Singleton)
     * @return Database
     */
    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Ambil objek PDO
     * @return \PDO
     */
    public function getPdo(): \PDO
    {
        return $this->pdo;
    }
}
