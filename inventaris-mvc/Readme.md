# Sistem Inventaris Barang (PHP MVC Native)

Aplikasi web manajemen inventaris barang dibangun menggunakan arsitektur **Model-View-Controller (MVC) murni** dengan PHP 8.x dan MySQL/MariaDB tanpa menggunakan framework.

## Fitur Aplikasi
1. **Autentikasi & Otorisasi:** Sistem login dengan session, hashing password (Bcrypt), dan notifikasi SweetAlert2.
2. **Master Data Barang & Kategori:** CRUD lengkap dengan penomoran kode barang otomatis (`ITM0001`, dst.) dan pemformatan mata uang Rupiah.
3. **Pencarian, Filter, & Paginasi:** Pencarian aman (*prepared statements*) dan pembatasan limit data (*whitelist*).
4. **Transaksi Stok Atomik:** Menggunakan *Database Transaction* untuk menjamin konsistensi stok dan mencegah stok bernilai negatif.
5. **Dashboard Dinamis:** Statistik total barang, stok, nilai total aset, tabel peringatan stok menipis (≤ 5), dan transaksi terbaru.
6. **Error Handling & Logger:** Halaman error 404 kustom dan pencatatan riwayat error ke berkas JSON di folder `logs/errors/`.

---

## Akun Demo Pengujian
- **Administrator:** `admin` / `123aaa123`
- **Staff Gudang:** `staff` / `321aaa321`
- **User Tambahan:** `Gaming111` / `AraAra123`

---

## Panduan Menjalankan Aplikasi

1. **Clone Repositori:**
   ```bash
   git clone https://github.com/Rizqqq/Pemrograman-web-a.git
   cd inventaris-mvc