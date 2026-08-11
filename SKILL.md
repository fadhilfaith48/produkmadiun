# SKILL — Panduan Skill untuk ProdukMadiun

Dokumen ini berisi "skill" / kompetensi yang harus dimiliki sebelum dan selama mengerjakan repo ini. Berguna untuk on-boarding developer/agent baru.

## 1. Prasyarat Teknis

- PHP 8.2+ terpasang & ada di PATH (`php -v`).
- Composer 2+ (`composer --version`).
- MySQL aktif (via XAMPP). Database `produkmadiun` harus dibuat:
  ```sql
  CREATE DATABASE IF NOT EXISTS produkmadiun CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
  ```
- Node.js + npm untuk Vite.

## 2. Setup Awal

```bash
composer install
cp .env.example .env      # lalu isi DB_USERNAME/DB_PASSWORD sesuai XAMPP
php artisan key:generate
php artisan storage:link
php artisan migrate
npm install
npm run build             # atau npm run dev untuk development
php artisan serve         # http://localhost:8000
```

> ⚠️ `php artisan db:seed` masih rusak (DatabaseSeeder tanpa namespace) — perbaiki dulu TODO #2 sebelum seeding.

## 3. Akun Default (dari Seeder, setelah diperbaiki)

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@produkmadiun.id | password123 |
| UMKM | umkm@produkmadiun.id | password123 |

## 4. Skill Inti yang Dibutuhkan

### 4.1 Laravel 12
- Konfigurasi via `bootstrap/app.php` (`withMiddleware`, `withExceptions`), bukan `Http/Kernel`.
- Eloquent: relasi, eager loading, `withCount`, casts, accessor.
- Validasi & Form Request / `$request->validate()`.
- Session flash (`with('success'|'error'|'info'|'warning')`).

### 4.2 Frontend
- Blade component/partial, layout `layouts.app`.
- Bootstrap 5 + Sass (`resources/sass`), Tailwind 4 (`@tailwindcss/vite`).
- Vite asset: `@vite(['resources/sass/app.scss', 'resources/js/app.js'])`.

### 4.3 Pola Bisnis ProdukMadiun
- Filter publik `is_active` / `is_verified` (WAJIB).
- Keranjang session `['qty','store_id']`.
- Checkout transaksional + penurunan stok.
- Upload via `Storage::disk('public')`.
- Slug unik untuk Product & Store.
- Order code generator (`Order::generateCode()`).

## 5. Checklist Sebelum Mengubah Kode

- [ ] Sudah baca `AGENTS.md` dan `ARCHITECTURE.md`.
- [ ] Tahu rute mana yang terpengaruh (`php artisan route:list`).
- [ ] Tidak mengubah skema DB tanpa migration baru.
- [ ] Menjaga konsistensi bahasa (Indonesia) pada UI & komentar.

## 6. Anti-Pattern yang Harus Dihindari

- ❌ Menambah rute panel tanpa middleware role.
- ❌ Checkout tanpa `DB::transaction`.
- ❌ Mengembalikan produk/toko non-aktif di halaman publik.
- ❌ Komit `.env` atau kredensial ke git.
- ❌ Membuat slug duplikat (cek unik dulu).
- ❌ Menambah komentar baru kecuali diminta.

## 7. Teknik Debugging

```bash
php artisan route:list          # cek rute
php artisan migrate:status      # cek migrasi (butuh MySQL aktif)
php -l app/Http/Controllers/*.php   # cek syntax
tail -f storage/logs/laravel.log    # lihat error (Linux/Mac/Git Bash)
```
