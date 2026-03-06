-- ============================================================
-- StokPro — Database Schema
-- Sistem Manajemen Stok
-- ============================================================

CREATE DATABASE IF NOT EXISTS stokpro CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE stokpro;

-- Tabel users (hak akses)
CREATE TABLE IF NOT EXISTS users (
    id       INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50)  NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nama     VARCHAR(100) NOT NULL,
    role     ENUM('admin','kasir') NOT NULL DEFAULT 'kasir',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Tabel produk
CREATE TABLE IF NOT EXISTS produk (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    kode        VARCHAR(20)  NOT NULL UNIQUE,
    nama        VARCHAR(150) NOT NULL,
    kategori    ENUM('Elektronik','Makanan','Minuman','Pakaian','Lainnya') NOT NULL,
    jenis       ENUM('fisik','digital') NOT NULL DEFAULT 'fisik',
    satuan      VARCHAR(20)  NOT NULL,
    harga_beli  INT          NOT NULL DEFAULT 0,
    harga_jual  INT          NOT NULL DEFAULT 0,
    stok        INT          NOT NULL DEFAULT 0,
    min_stok    INT          NOT NULL DEFAULT 5,
    deskripsi   TEXT,
    -- properti khusus BarangDigital
    link_download VARCHAR(255) DEFAULT NULL,
    masa_aktif    VARCHAR(50)  DEFAULT NULL,
    created_at  DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at  DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabel transaksi stok
CREATE TABLE IF NOT EXISTS transaksi (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    tipe         ENUM('masuk','keluar') NOT NULL,
    kode_produk  VARCHAR(20)  NOT NULL,
    nama_produk  VARCHAR(150) NOT NULL,
    qty          INT          NOT NULL,
    keterangan   VARCHAR(255) DEFAULT '',
    operator     VARCHAR(100) NOT NULL,
    created_at   DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (kode_produk) REFERENCES produk(kode) ON UPDATE CASCADE
);

-- ── Seed Data ──────────────────────────────────────────────

INSERT INTO users (username, password, nama, role) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'admin'),
('kasir', '$2y$10$TKh8H1.PrsqcCu7x8y4W1.GBpEh7VOy3blZIdwU1JNJt.gBTbCH7a', 'Kasir Toko',    'kasir');
-- password admin  = password
-- password kasir  = password

INSERT INTO produk (kode, nama, kategori, jenis, satuan, harga_beli, harga_jual, stok, min_stok, deskripsi) VALUES
('PRD-001', 'Laptop ASUS VivoBook', 'Elektronik', 'fisik',   'unit',    5500000, 7000000, 12, 5,  'Laptop 14 inci RAM 8GB'),
('PRD-002', 'Beras Premium 5kg',    'Makanan',    'fisik',   'karung',    55000,   75000, 50, 10, 'Beras pulen premium'),
('PRD-003', 'Air Mineral 600ml',    'Minuman',    'fisik',   'botol',      2000,    4000, 200,30, 'Air mineral bersih'),
('PRD-004', 'Kaos Polos Cotton',    'Pakaian',    'fisik',   'pcs',       45000,   90000,  8, 10, 'Cotton combed 30s'),
('PRD-005', 'Antivirus 1 Tahun',    'Elektronik', 'digital', 'lisensi',   80000,  150000,100,  5, 'Proteksi lengkap');

UPDATE produk SET link_download='https://dl.example.com', masa_aktif='1 Tahun' WHERE kode='PRD-005';
