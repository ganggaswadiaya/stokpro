<?php
/**
 * @file src/Models/Transaksi.php
 * @description Model transaksi stok masuk/keluar
 * @package StokPro\Models
 */

namespace StokPro\Models;

use StokPro\Config\Database;
use StokPro\Interfaces\Storable;

/**
 * Class Transaksi
 * Mencatat setiap perpindahan stok (masuk/keluar)
 */
class Transaksi implements Storable
{
    // ── Properties ───────────────────────────────────────
    private ?int   $id;
    private string $tipe;
    private string $kodeProduk;
    private string $namaProduk;
    private int    $qty;
    private string $keterangan;
    private string $operator;
    private string $createdAt;

    private \PDO $pdo;

    // ── Constructor ──────────────────────────────────────
    public function __construct(
        string $tipe,
        string $kodeProduk,
        string $namaProduk,
        int    $qty,
        string $keterangan = '',
        string $operator   = '',
        ?int   $id         = null,
        string $createdAt  = ''
    ) {
        $this->tipe       = $tipe;
        $this->kodeProduk = $kodeProduk;
        $this->namaProduk = $namaProduk;
        $this->qty        = $qty;
        $this->keterangan = $keterangan;
        $this->operator   = $operator;
        $this->id         = $id;
        $this->createdAt  = $createdAt ?: date('Y-m-d H:i:s');
        $this->pdo        = Database::getInstance()->getPdo();
    }

    // ── Getters ──────────────────────────────────────────
    public function getId():         ?int   { return $this->id; }
    public function getTipe():       string { return $this->tipe; }
    public function getKodeProduk(): string { return $this->kodeProduk; }
    public function getNamaProduk(): string { return $this->namaProduk; }
    public function getQty():        int    { return $this->qty; }
    public function getKeterangan(): string { return $this->keterangan; }
    public function getOperator():   string { return $this->operator; }
    public function getCreatedAt():  string { return $this->createdAt; }

    // ── Interface: Storable ──────────────────────────────
    /**
     * Simpan transaksi ke database
     * @return bool
     */
    public function simpan(): bool
    {
        $sql  = "INSERT INTO transaksi (tipe, kode_produk, nama_produk, qty, keterangan, operator)
                 VALUES (:tipe, :kode_produk, :nama_produk, :qty, :keterangan, :operator)";
        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute([
            ':tipe'        => $this->tipe,
            ':kode_produk' => $this->kodeProduk,
            ':nama_produk' => $this->namaProduk,
            ':qty'         => $this->qty,
            ':keterangan'  => $this->keterangan,
            ':operator'    => $this->operator,
        ]);
        if ($result) $this->id = (int)$this->pdo->lastInsertId();
        return $result;
    }

    /** @return bool */
    public function hapus(): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM transaksi WHERE id = :id");
        return $stmt->execute([':id' => $this->id]);
    }

    /** @return array */
    public function toArray(): array
    {
        return [
            'id'          => $this->id,
            'tipe'        => $this->tipe,
            'kode_produk' => $this->kodeProduk,
            'nama_produk' => $this->namaProduk,
            'qty'         => $this->qty,
            'keterangan'  => $this->keterangan,
            'operator'    => $this->operator,
            'created_at'  => $this->createdAt,
        ];
    }

    /** @param array $data @return self */
    public static function fromArray(array $data): self
    {
        return new self(
            $data['tipe'],
            $data['kode_produk'],
            $data['nama_produk'],
            (int)$data['qty'],
            $data['keterangan'] ?? '',
            $data['operator']   ?? '',
            isset($data['id']) ? (int)$data['id'] : null,
            $data['created_at'] ?? ''
        );
    }
}
