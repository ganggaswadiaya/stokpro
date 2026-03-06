# StokPro — Sistem Manajemen Stok (PHP)

## Struktur Proyek

```
stokpro/
├── index.php                    # Front Controller (titik masuk)
├── stokpro.sql                  # Schema & seed database
├── config/
│   ├── database.php             # Koneksi PDO (Singleton)
│   └── autoload.php             # PSR-4 autoloader + helper
├── src/
│   ├── Interfaces/
│   │   ├── Storable.php         # Interface simpan/hapus
│   │   └── Printable.php        # Interface info()
│   ├── Models/
│   │   ├── Barang.php           # Class produk fisik (OOP lengkap)
│   │   ├── BarangDigital.php    # Extends Barang (Inheritance)
│   │   ├── Transaksi.php        # Class transaksi stok
│   │   └── User.php             # Class user & autentikasi
│   └── Controllers/
│       ├── ProdukController.php # CRUD produk
│       ├── StokController.php   # Transaksi masuk/keluar
│       └── AuthController.php   # Login, logout, session
├── views/
│   ├── header.php / footer.php  # Template shared
│   ├── login.php                # Halaman login
│   ├── dashboard.php            # Dashboard
│   ├── produk.php               # Daftar produk
│   ├── produk_form.php          # Form tambah/edit
│   ├── masuk.php                # Stok masuk
│   ├── keluar.php               # Stok keluar
│   ├── riwayat.php              # Riwayat transaksi
│   └── laporan.php              # Laporan
└── public/css/
    └── style.css                # Stylesheet
```

## Pemenuhan Kriteria PDF

| Kriteria | Implementasi |
|---|---|
| Rancangan sesuai program | Manajemen stok: CRUD produk, stok masuk/keluar, laporan |
| Coding guidelines | PSR-4 namespace, PHPDoc, indentasi konsisten |
| Interface input/output | Form HTML, tabel, flash message |
| Tipe data, if/else, for/foreach | Di semua controller & view |
| Prosedur/fungsi/method | Method di setiap class |
| Array | array_filter, usort, array_slice, array_merge |
| Simpan & baca data | MySQL via PDO |
| Hak akses, properties, inheritance, polymorphism | `BarangDigital extends Barang`, override `info()`, `simpan()` |
| 2+ namespace/package | `StokPro\Models`, `StokPro\Controllers`, `StokPro\Interfaces`, `StokPro\Config` |
| External library | PDO (PHP extension), password_hash/verify |
| Basis data | MySQL — tabel: produk, transaksi, users |
| Dokumentasi | PHPDoc di semua file, class, method |

## Cara Instalasi

### Prasyarat
- PHP 8.0+
- MySQL 5.7+ / MariaDB
- Web server (Apache/Nginx) atau PHP built-in server

### Langkah

1. **Salin folder** `stokpro/` ke direktori web server:
   ```
   # Apache: /var/www/html/stokpro/
   # XAMPP : C:/xampp/htdocs/stokpro/
   # WAMP  : C:/wamp64/www/stokpro/
   ```

2. **Buat database** — buka phpMyAdmin atau MySQL CLI:
   ```sql
   source /path/to/stokpro/stokpro.sql
   ```

3. **Sesuaikan konfigurasi** di `config/database.php`:
   ```php
   private string $host     = 'localhost';
   private string $dbname   = 'stokpro';
   private string $username = 'root';
   private string $password = '';  // sesuaikan
   ```

4. **Akses** di browser:
   ```
   http://localhost/stokpro/
   ```

5. **Login**:
   - admin / password
   - kasir / password

### Dengan PHP Built-in Server
```bash
cd /path/to/stokpro
php -S localhost:8000
# Akses: http://localhost:8000
# Sesuaikan path CSS di views/header.php jika perlu
```

## Akun Default
| Username | Password | Role  | Hak Akses |
|---|---|---|---|
| admin   | password | admin | CRUD produk + semua fitur |
| kasir   | password | kasir | Lihat produk + transaksi stok |
