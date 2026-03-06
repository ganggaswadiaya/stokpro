<?php
/**
 * @file src/Controllers/ProdukController.php
 * @description Controller untuk CRUD produk
 * @package StokPro\Controllers
 */

namespace StokPro\Controllers;

use StokPro\Config\Database;
use StokPro\Models\Barang;
use StokPro\Models\BarangDigital;

/**
 * Class ProdukController
 * Mengelola semua operasi data produk
 */
class ProdukController
{
    private \PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance()->getPdo();
    }

    /**
     * Ambil semua produk dengan filter opsional
     * @param string $cari   kata kunci pencarian
     * @param string $kat    filter kategori
     * @return array
     */
    public function getAll(string $cari = '', string $kat = ''): array
    {
        $sql    = "SELECT * FROM produk WHERE 1=1";
        $params = [];

        if ($cari !== '') {
            $sql    .= " AND (nama LIKE :cari OR kode LIKE :cari2)";
            $params[':cari']  = "%{$cari}%";
            $params[':cari2'] = "%{$cari}%";
        }
        if ($kat !== '') {
            $sql    .= " AND kategori = :kat";
            $params[':kat'] = $kat;
        }
        $sql .= " ORDER BY nama ASC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Ambil produk berdasarkan kode
     * @param string $kode
     * @return array|null
     */
    public function getByKode(string $kode): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM produk WHERE kode = :kode LIMIT 1");
        $stmt->execute([':kode' => $kode]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    /**
     * Simpan produk baru
     * @param array $data POST data
     * @return array ['ok'=>bool, 'msg'=>string]
     */
    public function store(array $data): array
    {
        // Validasi input
        foreach (['kode','nama','kategori','satuan'] as $f) {
            if (empty($data[$f])) return ['ok'=>false, 'msg'=>"Field {$f} tidak boleh kosong"];
        }
        if ($this->getByKode($data['kode'])) {
            return ['ok'=>false, 'msg'=>'Kode produk sudah digunakan'];
        }

        // Buat objek — pilih class berdasarkan jenis (polymorphism via factory)
        $barang = $this->buatObjek($data);
        if (!$barang->simpan()) {
            return ['ok'=>false, 'msg'=>'Gagal menyimpan ke database'];
        }
        return ['ok'=>true, 'msg'=>"Produk \"{$data['nama']}\" berhasil ditambahkan"];
    }

    /**
     * Update produk
     * @param string $kode
     * @param array  $data
     * @return array
     */
    public function update(string $kode, array $data): array
    {
        $existing = $this->getByKode($kode);
        if (!$existing) return ['ok'=>false, 'msg'=>'Produk tidak ditemukan'];

        $merged = array_merge($existing, $data, ['kode'=>$kode]);
        $barang = $this->buatObjek($merged, (int)$existing['id']);
        if (!$barang->simpan()) {
            return ['ok'=>false, 'msg'=>'Gagal mengupdate database'];
        }
        return ['ok'=>true, 'msg'=>"Produk \"{$merged['nama']}\" berhasil diupdate"];
    }

    /**
     * Hapus produk
     * @param string $kode
     * @return array
     */
    public function destroy(string $kode): array
    {
        $existing = $this->getByKode($kode);
        if (!$existing) return ['ok'=>false, 'msg'=>'Produk tidak ditemukan'];

        $barang = Barang::fromArray($existing);
        if (!$barang->hapus()) {
            return ['ok'=>false, 'msg'=>'Gagal menghapus produk'];
        }
        return ['ok'=>true, 'msg'=>'Produk berhasil dihapus'];
    }

    /**
     * Factory method — buat objek Barang atau BarangDigital
     * @param array    $data
     * @param int|null $id
     * @return Barang
     */
    private function buatObjek(array $data, ?int $id = null): Barang
    {
        $jenis = $data['jenis'] ?? 'fisik';
        if ($jenis === 'digital') {
            return new BarangDigital(
                $data['kode'],
                $data['nama'],
                $data['kategori'],
                $data['satuan'],
                (int)($data['harga_beli'] ?? 0),
                (int)($data['harga_jual'] ?? 0),
                (int)($data['stok']       ?? 0),
                (int)($data['min_stok']   ?? 5),
                $data['deskripsi']         ?? '',
                $data['link_download']     ?? '',
                $data['masa_aktif']        ?? '',
                $id
            );
        }
        return new Barang(
            $data['kode'],
            $data['nama'],
            $data['kategori'],
            $data['satuan'],
            (int)($data['harga_beli'] ?? 0),
            (int)($data['harga_jual'] ?? 0),
            (int)($data['stok']       ?? 0),
            (int)($data['min_stok']   ?? 5),
            $data['deskripsi']         ?? '',
            $id
        );
    }

    /**
     * Statistik ringkasan untuk dashboard
     * @return array
     */
    public function getStats(): array
    {
        $produk      = $this->getAll();
        $totalProduk = count($produk);
        $nilaiStok   = array_sum(array_map(fn($p) => $p['harga_beli'] * $p['stok'], $produk));
        $lowStock    = array_filter($produk, fn($p) => $p['stok'] <= $p['min_stok']);

        return [
            'total_produk' => $totalProduk,
            'nilai_stok'   => $nilaiStok,
            'low_stock'    => array_values($lowStock),
            'low_count'    => count($lowStock),
        ];
    }

    /**
     * Laporan per kategori
     * @return array
     */
    public function getLaporanKategori(): array
    {
        $stmt = $this->pdo->query(
            "SELECT kategori,
                    COUNT(*)          AS jumlah_produk,
                    SUM(stok)         AS total_stok,
                    SUM(harga_beli*stok) AS nilai_stok
             FROM produk
             GROUP BY kategori
             ORDER BY kategori"
        );
        return $stmt->fetchAll();
    }
}
