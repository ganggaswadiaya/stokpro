<?php
/**
 * @file views/riwayat.php
 * @description Riwayat semua transaksi stok
 */
$pageTitle  = 'Riwayat';
$activePage = 'riwayat';
require __DIR__ . '/header.php';
?>

<h2>Riwayat Transaksi</h2>
<div class="sub">Semua pergerakan stok</div>

<div class="tbl-wrap">
  <?php if (empty($riwayat)): ?>
    <div class="empty">Belum ada data</div>
  <?php else: ?>
    <?php foreach ($riwayat as $t): ?>
    <div class="h-row">
      <div class="h-type <?= $t['tipe']==='masuk'?'in':'out' ?>"><?= strtoupper($t['tipe']) ?></div>
      <div class="h-info">
        <div class="h-name"><?= e($t['nama_produk']) ?></div>
        <div class="h-meta"><?= e($t['keterangan'] ?: '—') ?> · <?= e($t['created_at']) ?> · <?= e($t['operator']) ?></div>
      </div>
      <div class="h-qty <?= $t['tipe']==='masuk'?'text-green':'text-red' ?>">
        <?= $t['tipe']==='masuk'?'+':'-' ?><?= $t['qty'] ?>
      </div>
    </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>

<?php require __DIR__ . '/footer.php'; ?>
