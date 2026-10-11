<p align="center"><img src="public/img/logo.png" width="96" alt="Logo SiLaundry"></p>

# SiLaundry — Sistem Informasi Manajemen Laundry

Aplikasi web manajemen laundry berbasis **Laravel 13**, **MySQL**, **Tailwind CSS v4**, **Alpine.js**, dan
ikon **Lucide** dengan fitur **login multi role** (Administrator, Kasir, Pelanggan) dan **manajemen pengguna (CRUD)**.
Desain antarmuka mengikuti standar UI SIMPATFI (sidebar hijau, kartu ringkas, tanpa teks deskripsi).

## Fitur

- Login dengan email & password, redirect otomatis ke dashboard sesuai role.
- Pembatasan akses per role melalui `RoleMiddleware` (halaman 403 jika tidak berhak).
- Pembatasan login: 5 kali gagal → dikunci 60 detik (pesan tampil di form login).
- Manajemen pengguna (khusus Administrator): daftar + pencarian + filter role, tambah, edit,
  hapus (dengan dialog konfirmasi), dan pengaturan role.
- Akun ber-role Pelanggan otomatis memiliki data pelanggan (No HP & alamat).
- Pengaman: admin tidak bisa menghapus / mengubah peran akunnya sendiri; kasir maupun pelanggan
  yang sudah memiliki transaksi tidak bisa dihapus agar riwayat transaksi tetap utuh.

## Role & Hak Akses

| Fitur            | Administrator | Kasir | Pelanggan          |
|------------------|:-------------:|:-----:|:------------------:|
| Login            | ✓             | ✓     | ✓                  |
| Dashboard        | ✓             | ✓     | ✓                  |
| Melihat data     | ✓             | ✓     | ✓ (milik sendiri)  |
| Tambah/Edit/Hapus| ✓             | ✗     | ✗                  |
| Management User  | ✓             | ✗     | ✗                  |

## Instalasi

Membutuhkan PHP 8.3+ (disarankan 8.4), Composer, Node.js 20+, dan MySQL/MariaDB.

```bash
composer install
cp .env.example .env
php artisan key:generate
npm install
npm run build
```

> Hasil build (`public/build`) sudah ikut disimpan di repository, jadi `npm` hanya wajib bila tampilan diubah.

Atur koneksi database pada `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=silaundry
DB_USERNAME=root
DB_PASSWORD=
```

Buat database `silaundry`, lalu jalankan migrasi + seeder dan server:

```bash
php artisan migrate --seed
php artisan serve
```

Buka `http://localhost:8000`.

## Akun Demo (dari seeder)

| Role          | Email                       | Password      |
|---------------|-----------------------------|---------------|
| Administrator | admin@silaundry.test        | password123   |
| Kasir         | kasir@silaundry.test        | password123   |
| Kasir         | siti.kasir@silaundry.test   | password123   |
| Pelanggan     | pelanggan1@silaundry.test   | password123   |
| Pelanggan     | pelanggan2@silaundry.test   | password123   |

## Pengujian

```bash
php artisan test
```

Test fitur berada di `tests/Feature/MultiRoleLoginTest.php` dan `tests/Feature/UserManagementTest.php`
(menggunakan SQLite in-memory, butuh ekstensi `pdo_sqlite`).

## Struktur File Utama

| File | Fungsi |
|------|--------|
| `app/Models/User.php` | Model pengguna, konstanta & helper role |
| `app/Http/Controllers/AuthController.php` | Login, redirect sesuai role, logout |
| `app/Http/Controllers/UserController.php` | CRUD pengguna |
| `app/Http/Controllers/DashboardController.php` | Dashboard tiap role |
| `app/Http/Middleware/RoleMiddleware.php` | Pembatasan akses berdasarkan role |
| `app/Http/Requests/*UserRequest.php` | Validasi form pengguna |
| `routes/web.php` | Routing & grup middleware |
| `database/migrations`, `database/seeders` | Struktur database & data dummy |
| `resources/views` | Tampilan: layouts (app, guest), partials (sidebar, flash), components (`x-ui.*`, `x-form.*`), auth, dashboard, users, errors |
| `resources/css/app.css`, `resources/js/app.js` | Tema Tailwind (palet brand hijau), Alpine.js, ikon Lucide |
| `public/img/logo.png` | Logo SiLaundry (PNG transparan) |
| `screenshots/` | Bukti tangkapan layar pengujian LKP Pertemuan 5 |
