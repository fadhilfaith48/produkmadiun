# PRD — ProdukMadiun

## 1. Ringkasan

Platform e-commerce & direktori UMKM untuk Kabupaten Madiun. Menghubungkan pelaku UMKM (penjual) dengan pembeli (customer), dilengkapi panel kelola UMKM dan panel admin untuk moderasi/verifikasi.

**Stack:** PHP 8.2, Laravel 12, MySQL, Blade + Bootstrap 5 (Sass), Tailwind CSS, Vite.

## 2. Role Pengguna

| Role | Deskripsi | Hak Akses |
|------|-----------|-----------|
| `admin` | Pengelola platform | Verifikasi toko, approve ulasan, statistik platform |
| `umkm` | Penjual / pemilik toko | Kelola profil toko, produk, dan status pesanan |
| `customer` | Pembeli | Browsing katalog, keranjang, checkout, lacak pesanan |

## 3. Fitur

### 3.1 Publik (tanpa login)
- Homepage: banner, kategori, produk unggulan, produk terbaru, toko terverifikasi.
- Katalog produk `/katalog` — filter kategori, pencarian, sort harga/popularitas.
- Detail produk `/katalog/{slug}` — galeri, rating, ulasan, tombol order WhatsApp.
- Direktori toko `/toko` & detail toko `/toko/{slug}`.
- Keranjang belanja berbasis session (`/keranjang`).
- Checkout pesanan + order code + pelacakan pesanan (`/pesan/lacak`).
- Arahkan ke WhatsApp toko untuk konfirmasi pesanan.

### 3.2 Auth
- Register (memilih role: `customer` atau `umkm`), login, logout.
- Lupa password (email) & verifikasi email.

### 3.3 Panel UMKM (`/umkm`, middleware auth)
- Dashboard: total produk, pesanan, pendapatan, pesanan terbaru.
- Profil toko (nama, deskripsi, alamat, kecamatan, kontak, logo, banner).
- CRUD produk + galeri foto tambahan (`uploadImage` / `deleteImage`).
- Kelola pesanan & update status: `pending → confirmed → processing → shipped → completed / cancelled`.

### 3.4 Panel Admin (`/admin`, middleware auth)
- Dashboard statistik platform.
- Kelola toko: verifikasi / batalkan verifikasi.
- Approve ulasan.

### 3.5 API Publik (`/api/publik/*`, tanpa token)
- Produk (search, filter kategori & kecamatan, pagination).
- Detail produk (menambah `views`).
- Daftar UMKM terverifikasi + detail.
- Referensi: kategori (withCount), kecamatan, statistik platform.

## 4. Entitas & Relasi

```
User 1—1 Store 1—* Product *—1 Category
User 1—* Order 1—* OrderItem *—1 Product
Product 1—* ProductImage
Product 1—* Review *—1 User
Store 1—* Order
Banner (independen, ditampilkan di home)
Cart (session-based; tabel `carts` disediakan tapi belum dipakai aktif)
```

## 5. Aturan Bisnis

- Produk hanya tampil publik jika `is_active = true`.
- Toko tampil di direktori/home/API jika `is_verified = true` DAN `is_active = true`.
- Ulasan hanya tampil jika `is_approved = true`.
- Stok produk dikurangi saat checkout berhasil.
- Order code format: `ORD-YYYYMMDD-<uniqid uppercase>`.
- Pembayaran: `cod`, `transfer`, `whatsapp` (default `whatsapp`).
- Keranjang menyimpan `qty` dan `store_id` per produk (session).

## 6. Non-Fungsional

- Auth & CSRF via Laravel (web), Sanctum untuk API token.
- Session & cache berbasis database.
- Upload file ke disk `public` (`storage/app/public`, symlink `public/storage`).
- Satu toko hanya boleh dimiliki satu user (`stores.user_id`).
- Validasi input server-side di semua controller.

## 7. Kondisi Saat Ini (Evaluasi)

- **Bug fatal:** seeder tanpa namespace, `ReviewController::store` tidak ada, view `umkm.products.edit` hilang.
- **Keamanan:** middleware role belum terdaftar (Laravel 12 memakai `bootstrap/app.php`), panel admin/UMKM hanya dilindungi `auth`.
- **Logika:** checkout multi-toko salah, update profil toko meng-null-kan `is_active`.
- Detail lengkap di `TODO.md`.

## 8. Kriteria Rilis (Definition of Done)

1. `php artisan db:seed` berhasil.
2. Semua rute panel admin/UMKM terproteksi role yang benar.
3. CRUD produk UMKM lengkap (index, create, edit, hapus, galeri).
4. Checkout benar untuk produk multi-toko + transaksional (DB transaction).
5. `npm run build` & `php artisan route:list` tanpa error.
6. Ulasan bisa dibuat oleh customer & di-moderasi admin.
