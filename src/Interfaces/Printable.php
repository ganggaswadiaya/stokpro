<?php
/**
 * @file src/Interfaces/Printable.php
 * @description Interface untuk objek yang bisa ditampilkan sebagai teks
 * @package StokPro\Interfaces
 */

namespace StokPro\Interfaces;

/**
 * Interface Printable
 * Mendukung polymorphism melalui method info()
 */
interface Printable
{
    /**
     * Kembalikan deskripsi singkat objek
     * @return string
     */
    public function info(): string;
}
