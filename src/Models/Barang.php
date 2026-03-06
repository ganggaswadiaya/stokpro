<?php
/**
 * @file src/Models/Barang.php
 * @description Model produk fisik — OOP, properties, method, interface
 * @package StokPro\Models
 */

namespace StokPro\Models;

use StokPro\Config\Database;
use StokPro\Interfaces\Storable;
use StokPro\Interfaces\Printable;

/**
 * Class Barang
 * Representasi produk fisik dalam sistem stok.
 * Menerapkan: properties, hak akses (private/protected/public),
 * method, interface, dan menjadi parent class (inheritance).
 */
class Barang implements Storable, Printable
{
    // ── Properties (hak akses) ───────────────────────────
    /** @var int|null ID di database */
    protected ?int $id;

    /** @var string Kode unik produk */
    protected string $kode;

    /** @var string Nama produk */
    protected string $nama;

    /** @var string Kategori */
    protected string $kategori;

    /** @var string Satuan (pcs/kg/dll) */
    protected string $satuan;

    /** @var int Harga beli per unit */
    protected int $hargaBeli;

    /** @var int Harga jual per unit */
    protected int $hargaJual;

    /** @var int Jumlah stok saat ini */
    protected int $stok;

    /** @var int Stok minimum sebelum alert */
    protected int $minStok;

    /** @var string Deskripsi produk */
    protected string $deskripsi;

    /** @var string Jenis barang */
    protected string $jenis = 'fisik';

    /** @var \PDO Koneksi database */
    private \PDO $pdo;

    // ── Constructor ──────────────────────────────────────
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
     * @param int|null $id
     */
    public function __construct(
        string $kode,
        string $nama,
        string $kategori,
        string $satuan,
        int    $hargaBeli,
        int    $hargaJual,
        int    $stok     = 0,
        int    $minStok  = 5,
        string $deskripsi = '',
        ?int   $id       = null
    ) {
        $this->id        = $id;
        $this->kode      = strtoupper(trim($kode));
        $this->nama      = trim($nama);
        $this->kategori  = $kategori;
        $this->satuan    = $satuan;
        $this->hargaBeli = $hargaBeli;
        $this->hargaJual = $hargaJual;
        $this->stok      = $stok;
        $this->minStok   = $minStok;
        $this->deskripsi = $deskripsi;
        $this->pdo       = Database::getInstance()->getPdo();
    }

    // ── Getters ──────────────────────────────────────────
    public function getId():       ?int    { return $this->id; }
    public function getKode():     string  { return $this->kode; }
    public function getNama():     string  { return $this->nama; }
    public function getKategori(): string  { return $this->kategori; }
    public function getSatuan():   string  { return $this->satuan; }
    public function getHargaBeli():int     { return $this->hargaBeli; }
    public function getHargaJual():int     { return $this->hargaJual; }
    public function getStok():     int     { return $this->stok; }
    public function getMinStok():  int     { return $this->minStok; }
    public function getDeskripsi():string  { return $this->deskripsi; }
    public function getJenis():    string  { return $this->jenis; }

    // ── Calculated properties ────────────────────────────
    /** @return int Margin keuntungan per unit */
    public function getMargin(): int 
    {
        return $this->hargaJual - $this->hargaBeli; 
    }

    /** @return int Nilai total stok (harga beli × stok) */
    public function getNilaiStok(): int { return $this->hargaBeli * $this->stok; }

    /** @return bool Apakah stok di bawah minimum */
    public function isLowStock(): bool { return $this->stok <= $this->minStok; }

    // ── Stok methods ─────────────────────────────────────
    /**
     * Tambah stok
     * @param int $qty
     * @throws \InvalidArgumentException
     */
    public function tambahStok(int $qty): void
    {
        if ($qty <= 0) {
            throw new \InvalidArgumentException('Jumlah harus lebih dari 0');
        }
        $this->stok += $qty;
    }

    /**
     * Kurangi stok
     * @param int $qty
     * @throws \InvalidArgumentException|\RuntimeException
     */
    public function kurangiStok(int $qty): void
    {
        if ($qty <= 0) {
            throw new \InvalidArgumentException('Jumlah harus lebih dari 0');
        }
        if ($qty > $this->stok) {
            throw new \RuntimeException("Stok tidak cukup. Stok saat ini: {$this->stok}");
        }
        $this->stok -= $qty;
    }

    // ── Interface: Storable ──────────────────────────────
    /**
     * Simpan (insert atau update) ke database
     * @return bool
     */
    public function simpan(): bool
    {
        if ($this->id === null) {
            return $this->insert();
        }
        return $this->update();
    }

    /** @return bool */
    private function insert(): bool
    {
        $sql = "INSERT INTO produk
                    (kode, nama, kategori, jenis, satuan, harga_beli, harga_jual, stok, min_stok, deskripsi)
                VALUES
                    (:kode, :nama, :kategori, :jenis, :satuan, :harga_beli, :harga_jual, :stok, :min_stok, :deskripsi)";
        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute($this->bindParams());
        if ($result) $this->id = (int)$this->pdo->lastInsertId();
        return $result;
    }

    /** @return bool */
    private function update(): bool
    {
        $sql = "UPDATE produk SET
                    nama=:nama, kategori=:kategori, jenis=:jenis, satuan=:satuan,
                    harga_beli=:harga_beli, harga_jual=:harga_jual,
                    stok=:stok, min_stok=:min_stok, deskripsi=:deskripsi
                WHERE kode=:kode";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($this->bindParams());
    }

    /** @return array Parameter binding dasar */
    protected function bindParams(): array
    {
        return [
            ':kode'       => $this->kode,
            ':nama'       => $this->nama,
            ':kategori'   => $this->kategori,
            ':jenis'      => $this->jenis,
            ':satuan'     => $this->satuan,
            ':harga_beli' => $this->hargaBeli,
            ':harga_jual' => $this->hargaJual,
            ':stok'       => $this->stok,
            ':min_stok'   => $this->minStok,
            ':deskripsi'  => $this->deskripsi,
        ];
    }

    /**
     * Hapus dari database
     * @return bool
     */
    public function hapus(): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM produk WHERE kode = :kode");
        return $stmt->execute([':kode' => $this->kode]);
    }

    /**
     * Kembalikan sebagai array
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id'         => $this->id,
            'kode'       => $this->kode,
            'nama'       => $this->nama,
            'kategori'   => $this->kategori,
            'jenis'      => $this->jenis,
            'satuan'     => $this->satuan,
            'harga_beli' => $this->hargaBeli,
            'harga_jual' => $this->hargaJual,
            'stok'       => $this->stok,
            'min_stok'   => $this->minStok,
            'deskripsi'  => $this->deskripsi,
            'margin'     => $this->getMargin(),
            'nilai_stok' => $this->getNilaiStok(),
            'is_low'     => $this->isLowStock(),
        ];
    }

    // ── Interface: Printable ─────────────────────────────
    /**
     * Deskripsi singkat produk (Polymorphism — di-override subclass)
     * @return string
     */
    public function info(): string
    {
        return "[Fisik] {$this->nama} | Stok: {$this->stok} {$this->satuan}";
    }

    // ── Static factory methods ───────────────────────────
    /**
     * Buat objek Barang dari array data (misal dari PDO fetch)
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
            $data['deskripsi'] ?? '',
            isset($data['id']) ? (int)$data['id'] : null
        );
    }
}
