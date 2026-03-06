<?php

use StokPro\Controllers\AuthController;
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= e($pageTitle ?? 'StokPro') ?> — StokPro</title>
  <link rel="stylesheet" href="/stokpro/public/css/style.css" />
</head>

<body>

  <div class="topbar">
    <b>StokPro</b>
    <div class="topbar-right">
      <span><?= e($_SESSION['nama'] ?? '') ?></span>
      <span class="role-tag"><?= e($_SESSION['role'] ?? '') ?></span>
      <a href="/stokpro/index.php?action=logout" class="btn btn-sm">Keluar</a>
    </div>
  </div>

  <div class="layout">
    <nav class="sidebar">
      <div class="nav-group">Menu</div>
      <a href="/stokpro/index.php" class="nav-item <?= ($activePage ?? '') === 'dashboard' ? 'active' : '' ?>">Dashboard</a>
      <a href="/stokpro/index.php?page=produk" class="nav-item <?= ($activePage ?? '') === 'produk' ? 'active' : '' ?>">Produk</a>
      <a href="/stokpro/index.php?page=masuk" class="nav-item <?= ($activePage ?? '') === 'masuk' ? 'active' : '' ?>">Stok Masuk</a>
      <a href="/stokpro/index.php?page=keluar" class="nav-item <?= ($activePage ?? '') === 'keluar' ? 'active' : '' ?>">Stok Keluar</a>
      <div class="nav-group">Laporan</div>
      <a href="/stokpro/index.php?page=riwayat" class="nav-item <?= ($activePage ?? '') === 'riwayat' ? 'active' : '' ?>">Riwayat</a>
      <a href="/stokpro/index.php?page=laporan" class="nav-item <?= ($activePage ?? '') === 'laporan' ? 'active' : '' ?>">Laporan</a>
    </nav>

    <main class="main">
      <?php
      $flash = getFlash();
      if ($flash): ?>
        <div class="flash <?= e($flash['tipe']) ?>"><?= e($flash['msg']) ?></div>
      <?php endif; ?>