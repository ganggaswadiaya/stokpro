<?php

/**
 * @file views/produk.php
 * @description Halaman manajemen produk
 */

use StokPro\Controllers\AuthController;

$pageTitle  = 'Produk';
$activePage = 'produk';
require __DIR__ . '/header.php';
?>

<h2>Produk</h2>
<div class="sub">Kelola data produk</div>

<!-- Filter & Search -->
<form method="GET" action="/stokpro/index.php" class="search-bar">
  <input type="hidden" name="page" value="produk" />
  <input type="text" name="cari" value="<?= e($cari ?? '') ?>" placeholder="Cari nama / kode..." />
  <select name="kat">
    <option value="">Semua Kategori</option>
    <?php foreach (['Elektronik', 'Makanan', 'Minuman', 'Pakaian', 'Lainnya'] as $k): ?>
      <option <?= ($kat ?? '') === $k ? 'selected' : '' ?>><?= $k ?></option>
    <?php endforeach; ?>
  </select>
  <button type="submit" class="btn">Cari</button>
  <?php if (AuthController::isAdmin()): ?>
    <a href="/stokpro/index.php?page=produk&action=form" class="btn btn-primary btn-sm">+ Tambah</a>
  <?php endif; ?>
</form>

<!-- Tabel Produk -->
<div class="tbl-wrap">
  <table>
    <thead>
      <tr>
        <th>Kode</th>
        <th>Nama</th>
        <th>Kategori</th>
        <th>Harga Jual</th>
        <th>Stok</th>
        <?php if (AuthController::isAdmin()): ?><th></th><?php endif; ?>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($produk)): ?>
        <tr>
          <td colspan="6" class="empty">Tidak ada produk</td>
        </tr>
      <?php else: ?>
        <?php foreach ($produk as $p):
          $sc = $p['stok'] <= $p['min_stok'] ? 'low' : ($p['stok'] <= $p['min_stok'] * 2 ? 'mid' : 'ok');
        ?>
          <tr>
            <td class="text-blue" style="font-size:11px"><?= e($p['kode']) ?></td>
            <td>
              <?= e($p['nama']) ?>
              <div class="text-muted" style="font-size:11px"><?= e($p['jenis']) ?></div>
            </td>
            <td><span class="tag tag-cat"><?= e($p['kategori']) ?></span></td>
            <td><?= rupiah($p['harga_jual']) ?></td>
            <td><span class="tag tag-<?= $sc ?>"><?= $p['stok'] ?> <?= e($p['satuan']) ?></span></td>
            <?php if (AuthController::isAdmin()): ?>
              <td>
                <a href="/stokpro/index.php?page=produk&action=form&kode=<?= urlencode($p['kode']) ?>" class="btn btn-sm">Edit</a>
                <a href="/stokpro/index.php?page=produk&action=hapus&kode=<?= urlencode($p['kode']) ?>"
                  class="btn btn-sm btn-danger"
                  onclick="return confirm('Hapus produk <?= e($p['kode']) ?>?')">Hapus</a>
              </td>
            <?php endif; ?>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<?php require __DIR__ . '/footer.php'; ?>