<?php
/**
 * @file src/Controllers/AuthController.php
 * @description Controller untuk autentikasi dan session
 * @package StokPro\Controllers
 */

namespace StokPro\Controllers;

use StokPro\Models\User;

/**
 * Class AuthController
 * Login, logout, dan pengecekan sesi
 */
class AuthController
{
    /**
     * Proses login
     * @param string $username
     * @param string $password
     * @return array ['ok'=>bool, 'msg'=>string]
     */
    public function login(string $username, string $password): array
    {
        if (empty($username) || empty($password)) {
            return ['ok'=>false, 'msg'=>'Username dan password wajib diisi'];
        }
        $user = User::findByUsername($username);
        if (!$user || !$user->verifyPassword($password)) {
            return ['ok'=>false, 'msg'=>'Username atau password salah'];
        }
        $_SESSION['user_id']   = $user->getId();
        $_SESSION['username']  = $user->getUsername();
        $_SESSION['nama']      = $user->getNama();
        $_SESSION['role']      = $user->getRole();
        return ['ok'=>true, 'msg'=>'Login berhasil'];
    }

    /** Logout — hapus session */
    public function logout(): void
    {
        session_destroy();
    }

    /**
     * Cek apakah sudah login
     * @return bool
     */
    public static function check(): bool
    {
        return isset($_SESSION['username']);
    }

    /**
     * Cek apakah role admin
     * @return bool
     */
    public static function isAdmin(): bool
    {
        return ($_SESSION['role'] ?? '') === 'admin';
    }

    /**
     * Nama operator saat ini
     * @return string
     */
    public static function getNama(): string
    {
        return $_SESSION['nama'] ?? 'Unknown';
    }

    /**
     * Paksa login jika belum, redirect ke index
     */
    public static function requireLogin(): void
    {
        if (!self::check()) {
            header('Location: index.php');
            exit;
        }
    }
}
