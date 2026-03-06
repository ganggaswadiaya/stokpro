<?php
/**
 * @file views/dashboard.php
 * @description Halaman dashboard — ringkasan stok
 */
$pageTitle  = 'Dashboard';
$activePage = 'dashboard';
require __DIR__ . '/header.php';
?>

<h2>Dashboard</h2>
<div class="sub">Ringkasan kondisi stok</div>

<!-- Statistik -->
<div class="stats">
  <div class="stat">
    <div class="stat-label">Total Produk</div>
    <div class="stat-val"><?= $stats['total_produk'] ?></div>
  </div>
  <div class="stat">
    <div class="stat-label">Nilai Stok</div>
    <div class="stat-val text-green" style="font-size:15px"><?= rupiah($stats['nilai_stok']) ?></div>
  </div>
  <div class="stat">
    <div class="stat-label">Stok Menipis</div>
    <div class="stat-val text-red"><?= $stats['low_count'] ?></div>
  </div>
  <div class="stat">
    <div class="stat-label">Total Transaksi</div>
    <div class="stat-val"><?= $trxStats['total'] ?></div>
  </div>
  <div class="stat">
    <div class="stat-label">Masuk</div>
    <div class="stat-val text-green"><?= $trxStats['jml_masuk'] ?></div>
  </div>
  <div class="stat">
    <div class="stat-label">Keluar</div>
    <div class="stat-val text-red"><?= $trxStats['jml_keluar'] ?></div>
  </div>
</div>

<!-- Stok Menipis -->
<div class="tbl-wrap">
  <div class="tbl-head"><span>Stok Menipis</span></div>
  <table>
    <thead><tr><th>Produk</th><th>Kategori</th><th>Stok</th><th>Min</th></tr></thead>
    <tbody>
      <?php if (empty($stats['low_stock'])): ?>
        <tr><td colspan="4" class="empty">Semua stok aman</td></tr>
      <?php else: ?>
        <?php foreach ($stats['low_stock'] as $p): ?>
        <tr>
          <td><?= e($p['nama']) ?></td>
          <td><span class="tag tag-cat"><?= e($p['kategori']) ?></span></td>
          <td><span class="tag tag-low"><?= $p['stok'] ?> <?= e($p['satuan']) ?></span></td>
          <td><?= $p['min_stok'] ?></td>
        </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<?php require __DIR__ . '/footer.php'; ?>
