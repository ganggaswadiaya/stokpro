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

## Akun Default
| Username | Password | Role  | Hak Akses |
|---|---|---|---|
| admin   | password | admin | CRUD produk + semua fitur |
| kasir   | password | kasir | Lihat produk + transaksi stok |
