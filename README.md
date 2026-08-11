# ProdukMadiun

Platform e-commerce & direktori UMKM untuk produk lokal Kabupaten Madiun.

Toko UMKM dapat mendaftar, memverifikasi, mengelola produk, menerima pesanan, dan terhubung langsung dengan pembeli. Pembeli dapat menjelajahi katalog, melihat detail toko/produk, melihat ulasan, dan memesan.

## Fitur Utama

- **Publik** — beranda (katalog, kategori, toko terverifikasi), katalog produk dengan pencarian/filter/sortir, halaman produk dengan galeri & ulasan, halaman toko, keranjang berbasis session, checkout & lacak pesanan, pesan via WhatsApp.
- **API publik** — `GET /api/publik/*` untuk produk, detail produk, UMKM, kecamatan, kategori, dan statistik.
- **Panel UMKM** — dashboard, kelola profil toko, kelola produk (CRUD + galeri foto), kelola pesanan & status.
- **Panel Admin** — dashboard, verifikasi toko, persetujuan ulasan, daftar toko.
- **Role** — `admin`, `umkm`, `customer` via `RoleMiddleware` (`bootstrap/app.php`).

## Stack Teknologi

- Laravel 12 (PHP 8.2)
- MySQL
- Blade + Bootstrap 5 (Sass) + Tailwind 4 (Vite)

## Persyaratan

- PHP >= 8.2
- Composer
- Node.js & npm
- MySQL

## Setup Lokal

```bash
# 1. Clone & install dependensi
composer install
npm install

# 2. Konfigurasi environment
cp .env.example .env
php artisan key:generate
# edit .env: DB_DATABASE, DB_USERNAME, DB_PASSWORD, MAIL_*, dsb.

# 3. Migrasi & seed database
php artisan migrate
php artisan db:seed

# 4. Storage link untuk upload gambar produk/toko
php artisan storage:link

# 5. Jalankan dev server
npm run dev          # frontend (Vite)
php artisan serve    # backend (http://localhost:8000)
```

Untuk produksi gunakan `npm run build` untuk mengompilasi aset.

## Struktur Proyek

```
app/Http/Controllers/     # Admin/, Api/, Auth/, Umkm/ + publik
app/Models/               # User, Store, Product, ProductImage, Category,
                          #   Order, OrderItem, Review, Cart, Banner
database/migrations/      # skema DB
database/seeders/         # DatabaseSeeder
resources/views/          # Blade: admin, auth, cart, orders, products,
                          #   stores, umkm, partials, layouts
routes/web.php            # rute web (publik, auth, umkm, admin)
routes/api.php            # rute API publik (prefix /api/publik)
```

## Rute Utama

| Area | Rute | Keterangan |
| --- | --- | --- |
| Publik | `/`, `/katalog`, `/katalog/{slug}`, `/toko`, `/toko/{slug}` | Beranda, katalog, detail produk/toko |
| Keranjang | `/keranjang` | Keranjang berbasis session |
| Pesanan | `/pesan/checkout`, `/pesan/sukses/{code}`, `/pesan/lacak` | Checkout & lacak pesanan |
| UMKM | `/umkm/dashboard`, `/umkm/produk*`, `/umkm/pesanan*`, `/umkm/profil-toko` | Middleware `auth` + `role:umkm` |
| Admin | `/admin/dashboard`, `/admin/toko*`, `/admin/ulasan*` | Middleware `auth` + `role:admin` |
| API | `GET /api/publik/*` | API publik (produk, UMKM, kategori, kecamatan, statistik) |

Cek daftar lengkap: `php artisan route:list`.

## Konvensi

- Bahasa Indonesia untuk kode & komentar.
- Produk/toko publik difilter `is_active` / `is_verified`.
- Keranjang berbasis session; checkout transaksional (`DB::transaction`).
- Upload gambar ke disk `public` via `Storage::disk('public')->store(...)`.
- Controller memakai base `Controller`; view di-return dengan `view('...')`.

## Testing

```bash
composer test
```

## Keamanan

- `APP_DEBUG` harus `false` dan gunakan credential mail yang tidak terekspos untuk environment produksi.
- Batasi akses panel dengan middleware role, jangan hanya `auth`.