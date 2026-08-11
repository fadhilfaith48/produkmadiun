# Architecture — ProdukMadiun

## 1. Stack & Versi

| Komponen | Versi |
|----------|-------|
| PHP | 8.2 |
| Laravel Framework | 12.56 |
| MySQL | via XAMPP (port 3306) |
| Bootstrap | 5 (Sass) |
| Tailwind CSS | 4 |
| Vite | 7 |
| Laravel Sanctum | 4 |

## 2. Alur Request (Laravel 12)

```
public/index.php
  → bootstrap/app.php (Application::configure)
      → withRouting(web: routes/web.php, api: routes/api.php, health: /up)
      → withMiddleware(...)   ← TEMPAT REGISTER ALIAS MIDDLEWARE
      → withExceptions(...)
  → Routes → Controller → Model/DB → Blade view / JSON
```

> **PENTING:** Karena Laravel 12 tidak memuat `app/Http/Kernel.php`, semua konfigurasi middleware (termasuk alias `role`) harus dideklarasikan di `bootstrap/app.php` bagian `withMiddleware()`.

## 3. Arsitektur Folder

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Controller.php           # base controller
│   │   ├── HomeController.php       # beranda
│   │   ├── ProductController.php    # katalog & detail produk publik
│   │   ├── StoreController.php      # direktori & detail toko
│   │   ├── CartController.php       # keranjang berbasis session
│   │   ├── OrderController.php      # checkout, sukses, lacak, wa.me
│   │   ├── ReviewController.php     # KOSONG (belum ada method store)
│   │   ├── Admin/                   # DashboardController, ProductController (unused)
│   │   ├── Api/PublikController.php # API tanpa token prefix /api/publik
│   │   ├── Auth/                    # Login, Register, Forgot/Reset Password,
│   │   │                            #   Verification, ConfirmPassword
│   │   └── Umkm/                    # Dashboard, Product, Order
│   ├── Middleware/RoleMiddleware.php # cek role (belum terdaftar sebagai alias)
│   └── Kernel.php                    # DEAD CODE di Laravel 12
├── Models/                          # User, Store, Product, Category, ProductImage,
│                                    #   Order, OrderItem, Review, Cart, Banner
routes/
├── web.php                          # publik + auth + panel umkm/admin
└── api.php                          # API publik (/api/publik)
resources/views/                     # Blade (lihat PRD)
database/migrations/                 # 14 migrasi (users s.d. role)
database/seeders/DatabaseSeeder.php  # RUSAK (tanpa namespace)
```

## 4. Model & Skema Data

| Model | Tabel | Catatan |
|-------|-------|---------|
| User | users | `role` enum `['admin','umkm','customer']`, hasOne Store |
| Store | stores | `user_id` FK, `slug` unique, `is_verified`, `is_active` |
| Product | products | `store_id`, `category_id`, `slug` unique, `is_active`, `views` |
| ProductImage | product_images | galeri tambahan |
| Category | categories | nama, slug, icon |
| Order | orders | `order_code` unique, status enum, `payment_method` enum |
| OrderItem | order_items | snapshot `product_name`, `price`, `subtotal` |
| Review | reviews | `product_id`, `user_id`, `rating`, `is_approved` |
| Cart | carts | tabel disediakan; implementasi aktif = session |
| Banner | banners | `is_active`, `order` |

**Relasi utama:**
- `User 1—1 Store`, `Store 1—* Product 1—* ProductImage`
- `Product *—1 Category`, `Product 1—* Review`
- `Order 1—* OrderItem *—1 Product`, `User/Store 1—* Order`

## 5. Pola Arsitektur & Konvensi

- **Arsitektur:** MVC standar Laravel (fat controller → model Eloquent). Belum ada service/repository layer.
- **Keranjang:** disimpan di `session('cart')` berbentuk `[product_id => ['qty', 'store_id']]`.
- **Upload:** `Storage::disk('public')->store('products'|'stores/logos'|'stores/banners')`, symlink `public/storage` sudah dibuat.
- **Pembayaran & konfirmasi:** diarahkan ke `wa.me` toko (tidak ada payment gateway).
- **API:** endpoint publik tanpa autentikasi, response `{status, message, data}`; hasil list memakai `paginate()`.

## 6. Keamanan

- CSRF aktif untuk rute web.
- Upload divalidasi `image` + `max` ukuran.
- **GAP:** tidak ada proteksi role pada panel admin/UMKM (hanya `auth`), dan alias `role` tidak terdaftar. Lihat TODO #1.

## 7. Titik Integrasi

- **Madiun Info Hub** → konsumsi `GET /api/publik/*`.
- **WhatsApp** → `wa.me` untuk order & konfirmasi.
- **Gmail SMTP** → email reset password / verifikasi.

## 8. Diagram Alur Checkout (Saat Ini)

```
Tambah ke keranjang (session) → checkout → validasi form
  → Order::create (store_id = reset($cart)['store_id'])  ← BUG: 1 toko saja
  → order items createMany → decrement stock → hapus session
  → redirect wa.me (jika payment=whatsapp) | sukses page
```

> Perbaikan yang disarankan: bungkus `DB::transaction`, pisahkan order per toko atau beri `store_id` per item.
