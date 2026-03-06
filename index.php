<?php
/**
 * @file index.php
 * @description Front Controller — titik masuk utama aplikasi StokPro
 *
 * Struktur:
 *   index.php          — Front controller
 *   config/            — Konfigurasi database & autoloader
 *   src/Models/        — Class domain (Barang, BarangDigital, Transaksi, User)
 *   src/Controllers/   — Business logic (ProdukController, StokController, AuthController)
 *   src/Interfaces/    — Interface (Storable, Printable)
 *   views/             — Template HTML
 *   public/css/        — Stylesheet
 *
 * @package StokPro
 * @version 1.0
 */

// ── Bootstrap ────────────────────────────────────────────
session_start();
require_once __DIR__ . '/config/autoload.php';

use StokPro\Controllers\AuthController;
use StokPro\Controllers\ProdukController;
use StokPro\Controllers\StokController;

// ── Handle logout ────────────────────────────────────────
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    (new AuthController())->logout();
    redirect('/stokpro/index.php');
}

// ── Handle login POST ────────────────────────────────────
if (isset($_GET['action']) && $_GET['action'] === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $auth   = new AuthController();
    $result = $auth->login($_POST['username'] ?? '', $_POST['password'] ?? '');
    if ($result['ok']) {
        redirect('/stokpro/index.php');
    } else {
        $error = $result['msg'];
        require __DIR__ . '/views/login.php';
        exit;
    }
}

// ── Tampilkan login jika belum auth ──────────────────────
if (!AuthController::check()) {
    require __DIR__ . '/views/login.php';
    exit;
}

// ── Inisialisasi controller ───────────────────────────────
$produkCtrl = new ProdukController();
$stokCtrl   = new StokController();

// ── Routing ──────────────────────────────────────────────
$page   = $_GET['page']   ?? 'dashboard';
$action = $_GET['action'] ?? '';

// ── PRODUK ───────────────────────────────────────────────
if ($page === 'produk') {

    // Hapus produk
    if ($action === 'hapus' && AuthController::isAdmin()) {
        $result = $produkCtrl->destroy($_GET['kode'] ?? '');
        setFlash($result['msg'], $result['ok'] ? 'success' : 'error');
        redirect('/stokpro/index.php?page=produk');
    }

    // Form tambah / edit
    if ($action === 'form' && AuthController::isAdmin()) {
        $produk = !empty($_GET['kode']) ? $produkCtrl->getByKode($_GET['kode']) : null;
        require __DIR__ . '/views/produk_form.php';
        exit;
    }

    // Simpan (tambah / update)
    if ($action === 'simpan' && AuthController::isAdmin() && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $isUpdate = ($_POST['_method'] ?? '') === 'update';
        if ($isUpdate) {
            $result = $produkCtrl->update($_POST['kode_lama'], $_POST);
        } else {
            $result = $produkCtrl->store($_POST);
        }
        setFlash($result['msg'], $result['ok'] ? 'success' : 'error');
        redirect('/stokpro/index.php?page=produk');
    }

    // Daftar produk
    $cari   = $_GET['cari'] ?? '';
    $kat    = $_GET['kat']  ?? '';
    $produk = $produkCtrl->getAll($cari, $kat);
    require __DIR__ . '/views/produk.php';
    exit;
}

// ── STOK MASUK ───────────────────────────────────────────
if ($page === 'masuk') {
    if ($action === 'simpan' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $result = $stokCtrl->masuk(
            $_POST['kode_produk'] ?? '',
            (int)($_POST['qty']   ?? 0),
            $_POST['keterangan']  ?? '',
            AuthController::getNama()
        );
        setFlash($result['msg'], $result['ok'] ? 'success' : 'error');
        redirect('/stokpro/index.php?page=masuk');
    }
    $produk  = $produkCtrl->getAll();
    $riwayat = $stokCtrl->getRiwayat('masuk', 30);
    require __DIR__ . '/views/masuk.php';
    exit;
}

// ── STOK KELUAR ──────────────────────────────────────────
if ($page === 'keluar') {
    if ($action === 'simpan' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $result = $stokCtrl->keluar(
            $_POST['kode_produk'] ?? '',
            (int)($_POST['qty']   ?? 0),
            $_POST['keterangan']  ?? '',
            AuthController::getNama()
        );
        setFlash($result['msg'], $result['ok'] ? 'success' : 'error');
        redirect('/stokpro/index.php?page=keluar');
    }
    $produk  = $produkCtrl->getAll();
    $riwayat = $stokCtrl->getRiwayat('keluar', 30);
    require __DIR__ . '/views/keluar.php';
    exit;
}

// ── RIWAYAT ──────────────────────────────────────────────
if ($page === 'riwayat') {
    $riwayat = $stokCtrl->getRiwayat('', 200);
    require __DIR__ . '/views/riwayat.php';
    exit;
}

// ── LAPORAN ──────────────────────────────────────────────
if ($page === 'laporan') {
    $stats       = $produkCtrl->getStats();
    $trxStats    = $stokCtrl->getStatsTrx();
    $perKategori = $produkCtrl->getLaporanKategori();
    $nilaiTotal  = $stats['nilai_stok'];
    $totalProduk = $stats['total_produk'];

    // Top 5 stok terbanyak — Array sort
    $semuaProduk = $produkCtrl->getAll();
    usort($semuaProduk, fn($a, $b) => $b['stok'] - $a['stok']);
    $top5 = array_slice($semuaProduk, 0, 5);

    require __DIR__ . '/views/laporan.php';
    exit;
}

// ── DASHBOARD (default) ───────────────────────────────────
$stats    = $produkCtrl->getStats();
$trxStats = $stokCtrl->getStatsTrx();
require __DIR__ . '/views/dashboard.php';
