<?php
/**
 * @file src/Models/User.php
 * @description Model user dengan hak akses
 * @package StokPro\Models
 */

namespace StokPro\Models;

use StokPro\Config\Database;

/**
 * Class User
 * Mengelola autentikasi dan hak akses pengguna
 */
class User
{
    private ?int   $id;
    private string $username;
    private string $password;
    private string $nama;
    private string $role;
    private \PDO   $pdo;

    public function __construct(
        string $username,
        string $password,
        string $nama,
        string $role = 'kasir',
        ?int   $id   = null
    ) {
        $this->id       = $id;
        $this->username = $username;
        $this->password = $password;
        $this->nama     = $nama;
        $this->role     = $role;
        $this->pdo      = Database::getInstance()->getPdo();
    }

    public function getId():       ?int   { return $this->id; }
    public function getUsername(): string { return $this->username; }
    public function getNama():     string { return $this->nama; }
    public function getRole():     string { return $this->role; }

    /** @return bool apakah admin */
    public function isAdmin(): bool { return $this->role === 'admin'; }

    /**
     * Verifikasi password
     * @param string $plain
     * @return bool
     */
    public function verifyPassword(string $plain): bool
    {
        return password_verify($plain, $this->password);
    }

    /**
     * Cari user berdasarkan username
     * @param string $username
     * @return self|null
     */
    public static function findByUsername(string $username): ?self
    {
        $pdo  = Database::getInstance()->getPdo();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username LIMIT 1");
        $stmt->execute([':username' => $username]);
        $row = $stmt->fetch();
        if (!$row) return null;
        return new self($row['username'], $row['password'], $row['nama'], $row['role'], (int)$row['id']);
    }
}
