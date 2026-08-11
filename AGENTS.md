# AGENTS.md

Panduan untuk AI agent / asisten coding yang bekerja di repo ini. Baca ini sebelum mengubah kode.

## Project Overview

- **ProdukMadiun** — platform e-commerce & direktori UMKM Kabupaten Madiun.
- Stack: Laravel 12 (PHP 8.2), MySQL, Blade + Bootstrap 5 (Sass) + Tailwind 4, Vite.
- Entry config middleware Laravel 12 ada di `bootstrap/app.php`, **bukan** `app/Http/Kernel.php`.

## Commands

```bash
composer install          # install PHP deps
npm install && npm run dev   # frontend (Vite)
npm run build             # build produksi
php artisan serve         # jalankan dev server (port 8000)
php artisan migrate       # migrasi database
php artisan db:seed       # seeding (SAAT INI RUSAK — lihat TODO)
php artisan route:list    # cek rute
php -l <file>             # cek syntax PHP
composer test             # jalankan test suite
```

## Struktur Penting

```
app/Http/Controllers/     # Admin/, Api/, Auth/, Umkm/ + publik
app/Models/               # User, Store, Product, ProductImage, Category,
                          #   Order, OrderItem, Review, Cart, Banner
database/migrations/      # skema DB
database/seeders/         # DatabaseSeeder (sudah pakai namespace)
resources/views/          # Blade: admin, auth, cart, orders, products,
                          #   stores, umkm, partials, layouts
routes/web.php            # rute web (publik, auth, umkm, admin)
routes/api.php            # rute API publik (prefix /api/publik)
```

## Konvensi Kode

- Bahasa: kode & komentar sebagian besar Bahasa Indonesia (ikuti pola yang ada).
- Controller menggunakan `Controller` base class, view di-return dengan `view('...')`.
- Validasi via `$request->validate([...])`.
- Komentar: JANGAN tambahkan komentar baru kecuali diminta.
- Ikuti gaya file di sekitar (indent 4 spasi, docblock singkat di controller).
- Gunakan helper yang sudah ada, mis. `Order::generateCode()`, `Store::getWhatsappLinkAttribute()`.

## Pola Penting yang Harus Dihormati

1. **Produk/toko publik** harus difilter `is_active` / `is_verified`.
2. **Panel admin & UMKM** WAJIB pakai middleware role (lihat Todo #1) — jangan hanya `auth`.
3. **Keranjang berbasis session** (`session()->get('cart')`), tiap item = `['qty', 'store_id']`.
4. **Checkout harus transaksional** — jangan tambah logika checkout tanpa `DB::transaction`.
5. **Upload gambar** ke disk `public` via `Storage::disk('public')->store(...)`.
6. **Slug** harus unik (cek duplikat sebelum create/update, baik Product maupun Store).

## Bug yang Belum Diperbaiki (Jangan Dianggap Beres)

Detail: lihat `TODO.md`.

## Checklist Sebelum Selesai

- [ ] `php -l` pada semua file PHP yang diubah.
- [ ] `php artisan route:list` tetap valid.
- [ ] Tidak merusak konsistensi penamaan rute (`route('umkm.products.edit')`, dll).
- [ ] Jangan commit tanpa diminta.
