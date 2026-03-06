<?php
/**
 * @file views/keluar.php
 * @description Halaman stok keluar
 */
$pageTitle  = 'Stok Keluar';
$activePage = 'keluar';
require __DIR__ . '/header.php';
?>

<h2>Stok Keluar</h2>
<div class="sub">Catat pengeluaran barang</div>

<div class="form-section">
  <h3>Form Stok Keluar</h3>
  <form method="POST" action="/stokpro/index.php?page=keluar&action=simpan">
    <div class="form-group">
      <label>Produk</label>
      <select name="kode_produk" required>
        <?php foreach ($produk as $p): ?>
          <option value="<?= e($p['kode']) ?>"><?= e($p['kode']) ?> – <?= e($p['nama']) ?> (Stok: <?= $p['stok'] ?>)</option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="form-row">
      <div class="form-group">
        <label>Jumlah</label>
        <input type="number" name="qty" min="1" required placeholder="0"/>
      </div>
      <div class="form-group">
        <label>Keterangan</label>
        <input type="text" name="keterangan" placeholder="Penjualan, pemakaian, dll."/>
      </div>
    </div>
    <button type="submit" class="btn btn-primary">Simpan</button>
  </form>
</div>

<div class="tbl-wrap">
  <div class="tbl-head"><span>Riwayat Stok Keluar</span></div>
  <?php if (empty($riwayat)): ?>
    <div class="empty">Belum ada data</div>
  <?php else: ?>
    <?php foreach ($riwayat as $t): ?>
    <div class="h-row">
      <div class="h-type out">KELUAR</div>
      <div class="h-info">
        <div class="h-name"><?= e($t['nama_produk']) ?></div>
        <div class="h-meta"><?= e($t['keterangan'] ?: '—') ?> · <?= e($t['created_at']) ?> · <?= e($t['operator']) ?></div>
      </div>
      <div class="h-qty text-red">-<?= $t['qty'] ?></div>
    </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>

<?php require __DIR__ . '/footer.php'; ?>
