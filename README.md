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

## Interface Sistem
**Dasboard**
![alt text](https://github.com/ganggaswadiaya/stokpro/blob/main/pic/Screenshot%202026-03-06%20184732.png?raw=true)

**Tabel Produk**
![alt text](https://github.com/ganggaswadiaya/stokpro/blob/main/pic/Screenshot%202026-03-06%20184747.png?raw=true)

**Tambah Produk**
![alt text](https://github.com/ganggaswadiaya/stokpro/blob/main/pic/Screenshot%202026-03-06%20184804.png?raw=true)

**Stok Masuk**
![alt text](https://github.com/ganggaswadiaya/stokpro/blob/main/pic/Screenshot%202026-03-06%20184828.png?raw=true)

**Stok Keluar**
![alt text](https://github.com/ganggaswadiaya/stokpro/blob/main/pic/Screenshot%202026-03-06%20184841.png?raw=true)

**Riawayat**
![alt text](https://github.com/ganggaswadiaya/stokpro/blob/main/pic/Screenshot%202026-03-06%20184857.png?raw=true)

**Laporan**
![alt text](https://github.com/ganggaswadiaya/stokpro/blob/main/pic/Screenshot%202026-03-06%20184907.png?raw=true)

