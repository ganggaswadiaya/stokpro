<?php
/**
 * @file src/Controllers/StokController.php
 * @description Controller untuk transaksi stok masuk dan keluar
 * @package StokPro\Controllers
 */

namespace StokPro\Controllers;

use StokPro\Config\Database;
use StokPro\Models\Barang;
use StokPro\Models\Transaksi;

/**
 * Class StokController
 * Mengelola transaksi pergerakan stok
 */
class StokController
{
    private \PDO $pdo;
    private ProdukController $produkCtrl;

    public function __construct()
    {
        $this->pdo        = Database::getInstance()->getPdo();
        $this->produkCtrl = new ProdukController();
    }

    /**
     * Proses stok masuk
     * @param string $kodeProduk
     * @param int    $qty
     * @param string $keterangan
     * @param string $operator
     * @return array ['ok'=>bool, 'msg'=>string]
     */
    public function masuk(string $kodeProduk, int $qty, string $keterangan, string $operator): array
    {
        if ($qty <= 0) return ['ok'=>false, 'msg'=>'Jumlah harus lebih dari 0'];

        $row = $this->produkCtrl->getByKode($kodeProduk);
        if (!$row) return ['ok'=>false, 'msg'=>'Produk tidak ditemukan'];

        // Gunakan method pada model Barang
        $barang = Barang::fromArray($row);
        $barang->tambahStok($qty);
        $barang->simpan();

        // Catat transaksi
        $trx = new Transaksi('masuk', $kodeProduk, $barang->getNama(), $qty, $keterangan, $operator);
        $trx->simpan();

        return ['ok'=>true, 'msg'=>"+{$qty} stok \"{$barang->getNama()}\" berhasil dicatat"];
    }

    /**
     * Proses stok keluar
     * @param string $kodeProduk
     * @param int    $qty
     * @param string $keterangan
     * @param string $operator
     * @return array
     */
    public function keluar(string $kodeProduk, int $qty, string $keterangan, string $operator): array
    {
        if ($qty <= 0) return ['ok'=>false, 'msg'=>'Jumlah harus lebih dari 0'];

        $row = $this->produkCtrl->getByKode($kodeProduk);
        if (!$row) return ['ok'=>false, 'msg'=>'Produk tidak ditemukan'];

        $barang = Barang::fromArray($row);
        try {
            $barang->kurangiStok($qty);
        } catch (\Exception $e) {
            return ['ok'=>false, 'msg'=>$e->getMessage()];
        }
        $barang->simpan();

        $trx = new Transaksi('keluar', $kodeProduk, $barang->getNama(), $qty, $keterangan, $operator);
        $trx->simpan();

        return ['ok'=>true, 'msg'=>"-{$qty} stok \"{$barang->getNama()}\" berhasil dicatat"];
    }

    /**
     * Ambil riwayat transaksi
     * @param string $tipe  '' | 'masuk' | 'keluar'
     * @param int    $limit
     * @return array
     */
    public function getRiwayat(string $tipe = '', int $limit = 100): array
    {
        $sql    = "SELECT * FROM transaksi WHERE 1=1";
        $params = [];
        if ($tipe !== '') {
            $sql    .= " AND tipe = :tipe";
            $params[':tipe'] = $tipe;
        }
        $sql .= " ORDER BY created_at DESC LIMIT :lim";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':lim', $limit, \PDO::PARAM_INT);
        foreach ($params as $k => $v) $stmt->bindValue($k, $v);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Statistik transaksi
     * @return array
     */
    public function getStatsTrx(): array
    {
        $stmt = $this->pdo->query(
            "SELECT
                COUNT(*) AS total,
                SUM(CASE WHEN tipe='masuk'  THEN qty ELSE 0 END) AS total_masuk,
                SUM(CASE WHEN tipe='keluar' THEN qty ELSE 0 END) AS total_keluar,
                SUM(CASE WHEN tipe='masuk'  THEN 1   ELSE 0 END) AS jml_masuk,
                SUM(CASE WHEN tipe='keluar' THEN 1   ELSE 0 END) AS jml_keluar
             FROM transaksi"
        );
        return $stmt->fetch();
    }
}
