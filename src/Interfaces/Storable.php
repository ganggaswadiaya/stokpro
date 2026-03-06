<?php
/**
 * @file src/Interfaces/Storable.php
 * @description Interface untuk objek yang bisa disimpan ke database
 * @package StokPro\Interfaces
 */

namespace StokPro\Interfaces;

/**
 * Interface Storable
 * Memaksa class implementor menyediakan method CRUD dasar
 */
interface Storable
{
    /**
     * Simpan data ke penyimpanan
     * @return bool
     */
    public function simpan(): bool;

    /**
     * Hapus data dari penyimpanan
     * @return bool
     */
    public function hapus(): bool;

    /**
     * Kembalikan representasi array dari objek
     * @return array
     */
    public function toArray(): array;
}
