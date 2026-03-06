<?php
/**
 * @file views/laporan.php
 * @description Halaman laporan ringkasan stok
 */
$pageTitle  = 'Laporan';
$activePage = 'laporan';
require __DIR__ . '/header.php';
?>

<h2>Laporan</h2>
<div class="sub">Ringkasan data stok</div>

<!-- Statistik -->
<div class="stats" style="margin-bottom:20px">
  <div class="stat">
    <div class="stat-label">Nilai Stok</div>
    <div class="stat-val text-green" style="font-size:15px"><?= rupiah($nilaiTotal) ?></div>
  </div>
  <div class="stat">
    <div class="stat-label">Item Masuk</div>
    <div class="stat-val"><?= (int)$trxStats['total_masuk'] ?></div>
  </div>
  <div class="stat">
    <div class="stat-label">Item Keluar</div>
    <div class="stat-val"><?= (int)$trxStats['total_keluar'] ?></div>
  </div>
  <div class="stat">
    <div class="stat-label">Total Produk</div>
    <div class="stat-val"><?= $totalProduk ?></div>
  </div>
</div>

<div class="rep-grid">
  <!-- Per Kategori -->
  <div class="rep-card">
    <h4>Per Kategori</h4>
    <?php foreach ($perKategori as $k): ?>
    <div class="rep-row">
      <span><?= e($k['kategori']) ?></span>
      <span class="text-muted"><?= $k['jumlah_produk'] ?> produk · <?= $k['total_stok'] ?> unit</span>
      <span class="text-green"><?= rupiah($k['nilai_stok']) ?></span>
    </div>
    <?php endforeach; ?>
  </div>

  <!-- Top 5 Stok -->
  <div class="rep-card">
    <h4>Top 5 Stok Terbanyak</h4>
    <?php foreach ($top5 as $i => $p): ?>
    <div class="rep-row">
      <span class="text-muted"><?= $i+1 ?>.</span>
      <span style="flex:1"><?= e($p['nama']) ?></span>
      <span><?= $p['stok'] ?> <?= e($p['satuan']) ?></span>
    </div>
    <?php endforeach; ?>
  </div>
</div>

<?php require __DIR__ . '/footer.php'; ?>
