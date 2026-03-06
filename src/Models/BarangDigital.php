<?php
/**
 * @file src/Models/BarangDigital.php
 * @description Model produk digital — inheritance, overloading, polymorphism
 * @package StokPro\Models
 */

namespace StokPro\Models;

use StokPro\Config\Database;

/**
 * Class BarangDigital
 * Mewarisi class Barang (inheritance) dengan properti tambahan
 * untuk produk digital seperti software, lisensi, dll.
 *
 * Mendemonstrasikan:
 *  - Inheritance  : extends Barang
 *  - Polymorphism : override info()
 *  - Overloading  : override simpan() dan bindParams()
 */
class BarangDigital extends Barang
{
    // ── Properti tambahan ────────────────────────────────
    /** @var string URL untuk download produk digital */
    private string $linkDownload;

    /** @var string Masa aktif lisensi */
    private string $masaAktif;

    // ── Constructor (overloading) ────────────────────────
    /**
     * @param string $kode
     * @param string $nama
     * @param string $kategori
     * @param string $satuan
     * @param int    $hargaBeli
     * @param int    $hargaJual
     * @param int    $stok
     * @param int    $minStok
     * @param string $deskripsi
     * @param string $linkDownload  Properti tambahan
     * @param string $masaAktif     Properti tambahan
     * @param int|null $id
     */
    public function __construct(
        string $kode,
        string $nama,
        string $kategori,
        string $satuan,
        int    $hargaBeli,
        int    $hargaJual,
        int    $stok         = 0,
        int    $minStok      = 5,
        string $deskripsi    = '',
        string $linkDownload = '',
        string $masaAktif    = '',
        ?int   $id           = null
    ) {
        // Panggil constructor parent
        parent::__construct($kode, $nama, $kategori, $satuan, $hargaBeli, $hargaJual, $stok, $minStok, $deskripsi, $id);
        $this->linkDownload = $linkDownload;
        $this->masaAktif    = $masaAktif;
        $this->jenis        = 'digital';
    }

    // ── Getters ──────────────────────────────────────────
    public function getLinkDownload(): string { return $this->linkDownload; }
    public function getMasaAktif():    string { return $this->masaAktif; }

    // ── Override: simpan (overloading) ───────────────────
    /**
     * Simpan ke database — override untuk menyertakan kolom digital
     * @return bool
     */
    public function simpan(): bool
    {
        $pdo = Database::getInstance()->getPdo();
        if ($this->getId() === null) {
            $sql = "INSERT INTO produk
                        (kode, nama, kategori, jenis, satuan, harga_beli, harga_jual,
                         stok, min_stok, deskripsi, link_download, masa_aktif)
                    VALUES
                        (:kode, :nama, :kategori, :jenis, :satuan, :harga_beli, :harga_jual,
                         :stok, :min_stok, :deskripsi, :link_download, :masa_aktif)";
        } else {
            $sql = "UPDATE produk SET
                        nama=:nama, kategori=:kategori, jenis=:jenis, satuan=:satuan,
                        harga_beli=:harga_beli, harga_jual=:harga_jual,
                        stok=:stok, min_stok=:min_stok, deskripsi=:deskripsi,
                        link_download=:link_download, masa_aktif=:masa_aktif
                    WHERE kode=:kode";
        }
        $stmt   = $pdo->prepare($sql);
        $params = array_merge($this->bindParams(), [
            ':link_download' => $this->linkDownload,
            ':masa_aktif'    => $this->masaAktif,
        ]);
        return $stmt->execute($params);
    }

    // ── Override: toArray ────────────────────────────────
    /**
     * @return array
     */
    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'link_download' => $this->linkDownload,
            'masa_aktif'    => $this->masaAktif,
        ]);
    }

    // ── Override: info (polymorphism) ─────────────────────
    /**
     * Deskripsi produk digital — override dari Barang::info()
     * @return string
     */
    public function info(): string
    {
        return "[Digital] {$this->getNama()} | Masa Aktif: {$this->masaAktif} | Stok: {$this->getStok()}";
    }

    // ── Static factory ───────────────────────────────────
    /**
     * @param array $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            $data['kode'],
            $data['nama'],
            $data['kategori'],
            $data['satuan'],
            (int)$data['harga_beli'],
            (int)$data['harga_jual'],
            (int)$data['stok'],
            (int)$data['min_stok'],
            $data['deskripsi']    ?? '',
            $data['link_download'] ?? '',
            $data['masa_aktif']   ?? '',
            isset($data['id']) ? (int)$data['id'] : null
        );
    }
}
