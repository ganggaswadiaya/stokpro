<?php

/**
 * @file views/produk_form.php
 * @description Form tambah / edit produk
 */

use StokPro\Controllers\AuthController;

$isEdit     = !empty($produk);
$pageTitle  = $isEdit ? 'Edit Produk' : 'Tambah Produk';
$activePage = 'produk';
require __DIR__ . '/header.php';
?>

<h2><?= $isEdit ? 'Edit Produk' : 'Tambah Produk' ?></h2>
<div class="sub"><a href="/stokpro/index.php?page=produk">Kembali ke daftar</a></div>

<div class="form-section">
  <form method="POST" action="/stokpro/index.php?page=produk&action=simpan">
    <?php if ($isEdit): ?>
      <input type="hidden" name="_method" value="update" />
      <input type="hidden" name="kode_lama" value="<?= e($produk['kode']) ?>" />
    <?php endif; ?>

    <div class="form-group">
      <label>Kode Produk</label>
      <input type="text" name="kode" value="<?= e($produk['kode'] ?? '') ?>"
        <?= $isEdit ? 'readonly' : 'required' ?> placeholder="PRD-001" />
    </div>

    <div class="form-group">
      <label>Nama Produk</label>
      <input type="text" name="nama" value="<?= e($produk['nama'] ?? '') ?>" required />
    </div>

    <div class="form-row">
      <div class="form-group">
        <label>Kategori</label>
        <select name="kategori" required>
          <?php foreach (['Elektronik', 'Makanan', 'Minuman', 'Pakaian', 'Lainnya'] as $k): ?>
            <option <?= ($produk['kategori'] ?? '') === $k ? 'selected' : '' ?>><?= $k ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="form-group">
        <label>Jenis</label>
        <select name="jenis">
          <option value="fisik" <?= ($produk['jenis'] ?? '') === 'fisik' ? 'selected' : '' ?>>Fisik</option>
          <option value="digital" <?= ($produk['jenis'] ?? '') === 'digital' ? 'selected' : '' ?>>Digital</option>
        </select>
      </div>
    </div>

    <div class="form-group">
      <label>Satuan</label>
      <input type="text" name="satuan" value="<?= e($produk['satuan'] ?? '') ?>" required placeholder="pcs / kg / botol" />
    </div>

    <div class="form-row">
      <div class="form-group">
        <label>Harga Beli (Rp)</label>
        <input type="number" name="harga_beli" value="<?= $produk['harga_beli'] ?? 0 ?>" min="0" />
      </div>
      <div class="form-group">
        <label>Harga Jual (Rp)</label>
        <input type="number" name="harga_jual" value="<?= $produk['harga_jual'] ?? 0 ?>" min="0" />
      </div>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label>Stok <?= $isEdit ? '' : 'Awal' ?></label>
        <input type="number" name="stok" value="<?= $produk['stok'] ?? 0 ?>" min="0" />
      </div>
      <div class="form-group">
        <label>Stok Minimum</label>
        <input type="number" name="min_stok" value="<?= $produk['min_stok'] ?? 5 ?>" min="0" />
      </div>
    </div>

    <div class="form-group">
      <label>Deskripsi</label>
      <textarea name="deskripsi" rows="2"><?= e($produk['deskripsi'] ?? '') ?></textarea>
    </div>

    <!-- Field khusus digital -->
    <div id="digital-fields" style="display:<?= ($produk['jenis'] ?? '') === 'digital' ? 'block' : 'none' ?>">
      <div class="form-row">
        <div class="form-group">
          <label>Link Download</label>
          <input type="text" name="link_download" value="<?= e($produk['link_download'] ?? '') ?>" />
        </div>
        <div class="form-group">
          <label>Masa Aktif</label>
          <input type="text" name="masa_aktif" value="<?= e($produk['masa_aktif'] ?? '') ?>" placeholder="1 Tahun" />
        </div>
      </div>
    </div>

    <div class="form-actions">
      <button type="submit" class="btn btn-primary">Simpan</button>
      <a href="/stokpro/index.php?page=produk" class="btn">Batal</a>
    </div>
  </form>
</div>

<script>
  document.querySelector('[name=jenis]').addEventListener('change', function() {
    document.getElementById('digital-fields').style.display = this.value === 'digital' ? 'block' : 'none';
  });
</script>

<?php require __DIR__ . '/footer.php'; ?>