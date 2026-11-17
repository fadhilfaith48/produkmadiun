# ProdukMadiun

![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-MariaDB-4479A1?logo=mysql&logoColor=white)
![Blade](https://img.shields.io/badge/Blade-Bootstrap%205-7952B3?logo=bootstrap&logoColor=white)
![Tailwind](https://img.shields.io/badge/Tailwind-4-06B6D4?logo=tailwindcss&logoColor=white)
![Vite](https://img.shields.io/badge/Vite-7-646CFF?logo=vite&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-green)

**ProdukMadiun** adalah platform e-commerce sekaligus direktori UMKM untuk produk lokal Kabupaten Madiun. Pelaku UMKM dapat mendaftar, melengkapi profil toko, mengelola produk beserta galeri foto, dan memproses pesanan. Pembeli dapat menjelajahi katalog, melihat detail produk/toko, membaca ulasan, memasukkan produk ke keranjang, checkout, lalu melacak pesanannya — tanpa harus punya akun.

Dibangun sebagai satu aplikasi Laravel (Blade + Vite), lengkap dari antarmuka publik, panel UMKM, panel admin, sampai API publik.

## Latar Belakang

Banyak UMKM di Kabupaten Madiun belum punya kanal penjualan digital yang rapi. ProdukMadiun dibuat untuk menjembatani itu: satu tempat untuk katalog produk lokal, direktori toko terverifikasi, dan alur pesanan yang sederhana.

Proyek ini adalah tugas PKL jurusan Rekayasa Perangkat Lunak. Pengerjaannya berlangsung bertahap:

- **Tahap awal** — membangun halaman publik dan panel dengan data contoh.
- **Tahap berikutnya** — menyambungkan alur nyata: autentikasi + verifikasi email, role admin/UMKM/customer, CRUD produk dan galeri, checkout transaksional multi-toko, moderasi ulasan, serta API publik untuk konsumen eksternal (Madiun Info Hub).

## Fitur

### Publik (tanpa login)

- Beranda: banner aktif, kategori, produk terpopuler, produk terbaru, dan toko terverifikasi.
- Katalog `/katalog`: pencarian nama produk, filter kategori, dan sortir (terbaru / harga terendah / harga tertinggi / terpopuler).
- Detail produk `/katalog/{slug}`: galeri foto utama + foto tambahan, harga, stok, deskripsi, rating rata-rata, daftar ulasan yang sudah disetujui, form ulasan, dan tombol pesan via WhatsApp.
- Direktori toko `/toko` dan detail toko `/toko/{slug}`: profil, kontak, dan produk toko.
- Ulasan bisa dikirim pengunjung (nama tamu) maupun user login; ulasan tampil setelah disetujui admin.

### Belanja & Pesanan

- Keranjang berbasis session (`/keranjang`): tambah, ubah qty, hapus item, kosongkan.
- Validasi stok saat menambah/mengubah qty, bukan hanya saat checkout.
- Checkout guest maupun login: isi nama, HP, WhatsApp, alamat, catatan, dan metode pembayaran (`cod`, `transfer`, `whatsapp`).
- **Satu pesanan per toko** — keranjang lintas toko otomatis dipecah menjadi beberapa order.
- Checkout dibungkus `DB::transaction` + `lockForUpdate()` agar stok tidak oversell saat dua checkout berjalan bersamaan.
- Halaman sukses menampilkan semua order yang terbentuk beserta kode order.
- Lacak pesanan `/pesan/lacak` menggunakan kode `ORD-YYYYMMDD-XXXXXXXX`.
- Konfirmasi pesanan diarahkan ke WhatsApp toko (format nomor `08xx` otomatis menjadi `62xx`).

### Panel UMKM (`/umkm`)

- Middleware `auth` + `verified` + `role:umkm`.
- Dashboard: jumlah produk, pesanan, pesanan menunggu, pendapatan, pesanan terbaru.
- Profil toko: nama, deskripsi, alamat, desa, kecamatan, kontak, logo, banner.
- Slug toko dibuat unik otomatis (aman saat nama toko sama).
- CRUD produk: nama, kategori, harga, stok, satuan, berat, deskripsi, status aktif, foto utama, dan galeri foto tambahan.
- Slug produk unik otomatis; foto disimpan di disk `public`.
- Kelola pesanan: lihat detail dan ubah status mengikuti transisi yang valid.

### Panel Admin (`/admin`)

- Middleware `auth` + `verified` + `role:admin`.
- Dashboard statistik platform: produk, toko, pesanan, pendapatan, pesanan pending, toko menunggu verifikasi, produk terpopuler.
- Verifikasi / batalkan verifikasi toko. Toko terverifikasi + aktif otomatis tampil di direktori publik.
- Moderasi ulasan: setujui (tampil publik), sembunyikan, edit rating/komentar, atau hapus.
- Kelola banner beranda: tambah, edit, aktif/nonaktif, urutan tampil, hapus.

### API Publik

Prefix `/api/publik`, tanpa token — dipakai konsumen eksternal seperti *Madiun Info Hub*.

- Produk: pencarian, filter kategori & kecamatan, pagination.
- Detail produk (menambah `views`).
- Daftar UMKM terverifikasi + detail toko.
- Referensi: kategori (dengan jumlah produk), kecamatan, statistik platform.

## Tech Stack

| Komponen | Teknologi |
| --- | --- |
| Backend | Laravel 12 (PHP 8.2), arsitektur MVC |
| Autentikasi | Laravel Auth (session), Laravel Sanctum 4 terpasang |
| Database | MySQL / MariaDB |
| Tampilan | Blade + Bootstrap 5 (CDN) + Bootstrap Icons |
| Styling/bundling | Sass + Tailwind CSS 4 + Vite 7 |
| Upload gambar | Disk `public` (`storage/app/public` via symlink) |
| Email | SMTP (verifikasi email, reset password) |
| API | REST JSON, prefix `/api/publik` |

> Catatan: layout utama saat ini memuat Bootstrap dari CDN. Pipeline Sass/Tailwind tetap tersedia lewat Vite untuk pengembangan berikutnya.

## Struktur Proyek

```
produkmadiun/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── HomeController.php        # beranda
│   │   │   ├── ProductController.php     # katalog & detail produk
│   │   │   ├── StoreController.php       # direktori & detail toko
│   │   │   ├── CartController.php        # keranjang session
│   │   │   ├── OrderController.php       # checkout, sukses, lacak, WhatsApp
│   │   │   ├── ReviewController.php      # kirim ulasan publik
│   │   │   ├── Admin/                    # dashboard, toko, ulasan, banner
│   │   │   ├── Api/PublikController.php  # API publik
│   │   │   ├── Auth/                     # login, register, verifikasi, reset password
│   │   │   └── Umkm/                     # dashboard, produk, pesanan
│   │   └── Middleware/RoleMiddleware.php # cek role (alias `role`)
│   └── Models/                           # User, Store, Product, ProductImage,
│                                         #   Category, Order, OrderItem, Review, Banner
├── bootstrap/app.php                     # registrasi rute & middleware (Laravel 12)
├── config/                               # konfigurasi Laravel
├── database/
│   ├── migrations/                       # 14 migrasi skema
│   └── seeders/DatabaseSeeder.php        # data demo
├── resources/
│   ├── views/                            # Blade: admin, auth, cart, orders,
│   │                                     #   products, stores, umkm, partials, layouts
│   ├── sass/                             # Bootstrap (Sass)
│   └── js/                               # entry Vite
├── routes/
│   ├── web.php                           # rute web publik, auth, UMKM, admin
│   └── api.php                           # rute API publik
├── tests/                                # PHPUnit feature test
├── ARCHITECTURE.md · PRD.md · TODO.md
├── WORKFLOW.md · UJI_MANUAL.md · SKILL.md
└── AGENTS.md
```

## Cara Menjalankan

### Prasyarat

- PHP 8.2+ dan Composer
- Node.js 20+ dan npm
- MySQL / MariaDB aktif (mis. XAMPP/Laragon)

### Instalasi

```bash
# 1. Dependensi
composer install
npm install

# 2. Konfigurasi environment
cp .env.example .env
php artisan key:generate
# edit .env: DB_DATABASE, DB_USERNAME, DB_PASSWORD, MAIL_*, dsb.

# 3. Buat database (contoh)
#    CREATE DATABASE produkmadiun CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

# 4. Migrasi + data demo
php artisan migrate:fresh --seed

# 5. Symlink upload gambar
php artisan storage:link

# 6. Jalankan
npm run dev          # Vite (development)
php artisan serve    # http://127.0.0.1:8000
```

Untuk produksi gunakan `npm run build` lalu `php artisan config:cache && php artisan route:cache && php artisan view:cache`.

## Akun Demo

Password semua akun: `password123`

| Role | Email | Keterangan |
| --- | --- | --- |
| Admin | `admin@produkmadiun.id` | Akses panel admin |
| UMKM | `umkm@produkmadiun.id` | Kripik Tempe Bu Sari (terverifikasi) |
| UMKM | `umkm2@produkmadiun.id` | Bathik Sekar Arum (belum terverifikasi) |
| UMKM | `umkm3@produkmadiun.id` | Sayur Segar Madiun (terverifikasi) |
| Customer | `customer@produkmadiun.id` | Akun pembeli |

Halaman publik bisa dibuka tanpa login. Pembeli juga bisa checkout sebagai tamu.

## Halaman & Rute Utama

| Area | Rute | Keterangan |
| --- | --- | --- |
| Publik | `/` | Beranda (banner, kategori, produk, toko) |
| Publik | `/katalog`, `/katalog/{slug}` | Katalog & detail produk |
| Publik | `/toko`, `/toko/{slug}` | Direktori & detail toko |
| Publik | `POST /ulasan` | Kirim ulasan (menunggu approval) |
| Keranjang | `/keranjang` | Keranjang berbasis session |
| Pesanan | `/pesan/checkout`, `POST /pesan/simpan` | Checkout |
| Pesanan | `/pesan/sukses/{code}`, `/pesan/lacak` | Sukses & lacak pesanan |
| Auth | `/login`, `/register`, `/password/reset` | Autentikasi & reset password |
| UMKM | `/umkm/dashboard`, `/umkm/profil-toko` | Dashboard & profil toko |
| UMKM | `/umkm/produk*`, `/umkm/pesanan*` | CRUD produk & kelola pesanan |
| Admin | `/admin/dashboard`, `/admin/toko`, `/admin/ulasan`, `/admin/banner` | Panel admin |
| API | `GET /api/publik/*` | Produk, UMKM, kategori, kecamatan, statistik |

Daftar lengkap: `php artisan route:list`.

## Aturan Bisnis Penting

- Produk hanya tampil publik jika `products.is_active = true` **dan** tokonya `is_verified = true` serta `is_active = true` (scope `availableForPublic`).
- Ulasan hanya tampil jika `is_approved = true`.
- Stok produk dikurangi saat checkout berhasil, di dalam satu transaksi database.
- Order code: `ORD-YYYYMMDD-` + 8 karakter acak, dijamin unik.
- Transisi status pesanan: `pending → confirmed → processing → shipped → completed`, dengan `cancelled` dari tiga status awal.
- Keranjang session menyimpan `qty` dan `store_id` per produk.
- Panel UMKM/admin wajib lolos `auth`, `verified`, dan `role`; semua query UMKM dibatasi `store_id` milik user login.
- Upload gambar divalidasi (`image`, batas ukuran) dan disimpan di disk `public`.

## Verifikasi

```bash
# Test suite
composer test          # 50 test, 117 assertion

# Rute
php artisan route:list

# Build aset
npm run build

# Cek syntax file PHP yang diubah
php -l app/Http/Controllers/OrderController.php
```

Status saat ini: seluruh 50 test lulus, `route:list` valid, dan `npm run build` sukses.

## Dokumentasi

Dokumen level proyek ada di root repo:

- [`PRD.md`](PRD.md) — kebutuhan produk, role, entitas, aturan bisnis
- [`ARCHITECTURE.md`](ARCHITECTURE.md) — stack, alur request, skema model
- [`WORKFLOW.md`](WORKFLOW.md) — alur kerja Git, verifikasi, rilis
- [`UJI_MANUAL.md`](UJI_MANUAL.md) — checklist uji manual per peran
- [`TODO.md`](TODO.md) — daftar pekerjaan & hasil audit
- [`SKILL.md`](SKILL.md) — prasyarat & skill untuk onboarding developer
- [`AGENTS.md`](AGENTS.md) — aturan kerja untuk AI coding assistant

## Lisensi

Dirilis di bawah [MIT License](https://opensource.org/licenses/MIT) (lihat `license` pada `composer.json`).

## Kredit

Dibuat oleh **fadhilfaith48** — [github.com/fadhilfaith48/produkmadiun](https://github.com/fadhilfaith48/produkmadiun).
