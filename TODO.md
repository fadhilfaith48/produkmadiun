# TODO — ProdukMadiun

Daftar pekerjaan berdasarkan audit kode. Diurutkan dari prioritas tertinggi.

## 🔴 Prioritas 1 — Bug Fatal & Keamanan

- [x] **#1 Middleware role belum aktif (KEAMANAN KRITIS)**
  - Daftarkan alias `role` di `bootstrap/app.php` (Laravel 12, bukan `app/Http/Kernel.php`).
  - Hapus `app/Http/Kernel.php` (dead code) untuk mencegah kebingungan.
  - Terapkan: panel admin → `middleware('auth','role:admin')`, panel UMKM → `middleware('auth','role:umkm')`.
  - Catatan: `RoleMiddleware` sendiri sudah ada (`app/Http/Middleware/RoleMiddleware.php`).

- [x] **#2 Seeder rusak**
  - Tambahkan `namespace Database\Seeders;` di `database/seeders/DatabaseSeeder.php`.
  - Verifikasi dengan `php artisan db:seed`.

- [x] **#3 `ReviewController::store` tidak ada**
  - Rute `POST /ulasan` (`reviews.store`) menunjuk method yang tidak ada → 500.
  - Implementasikan `store()`: validasi `product_id`, `rating`, `comment`; simpan dengan `is_approved=false`; atau hapus rute jika fitur ulasan dihapus.
  - Tambahkan tampilan form ulasan di halaman produk bila perlu.

- [x] **#4 View `umkm.products.edit` hilang**
  - Buat `resources/views/umkm/products/edit.blade.php` (referensi dari `Umkm\ProductController::edit`).
  - Konsistenkan `create()` agar juga memakai `view('umkm.products.create')` (saat ini memakai `products.create`).

## 🟠 Prioritas 2 — Bug Logika Bisnis

- [x] **#5 `updateProfile` meng-null-kan `is_active`**
  - Form `umkm.profile` tidak punya field `is_active`, tapi controller membaca `$request->only([...'is_active'])` → toko hilang dari listing publik.
  - Solusi: hapus `is_active` dari `$request->only()` di `Umkm\DashboardController::updateProfile`, atau tambahkan checkbox + default true.

- [x] **#6 Checkout multi-toko & transaksional**
  - `OrderController::store` memakai `reset($cart)['store_id']` → pesanan dari banyak toko salah dimasukkan ke satu toko.
  - Bungkus seluruh proses dalam `DB::transaction`.
  - Validasi stok ulang sebelum simpan; cek `Product::find()` null (produk terhapus).
  - Putuskan strategi: (a) larang keranjang lintas toko, atau (b) buat 1 order per toko.

- [x] **#7 Toko non-aktif/terverifikasi masih tampil**
  - `StoreController::show()` tidak filter `is_verified` & `is_active` (index sudah benar). Tambahkan filter.

- [x] **#8 Slug toko bisa duplikat**
  - `Store::boot()` hanya set slug jika kosong; `updateProfile` selalu set slug dari nama tanpa cek unik (kolom `slug` unique → error SQL).
  - Terapkan cek duplikat seperti yang dipakai Product (`where('slug','like',...)->count()`).

## 🟡 Prioritas 3 — Kebersihan & Konsistensi

- [x] **#9 Role enum & helper tidak konsisten**
  - Migration `add_role` memakai `['user','umkm','admin']`, base table `['admin','umkm','customer']`.
  - `User::isCustomer()` cek `customer || !$role` — beri kejelasan untuk role `user`.

- [x] **#10 Dead code & folder typo**
  - Hapus `app/Http/Controllers/Admin/ProductController.php` (tidak terdaftar di route, referensi view `admin.products.*` yang tidak ada) ATAU daftarkan + buat view-nya.
  - Hapus folder kosong `resourcesviewsstores/` (typo di root).

- [x] **#11 N+1 query**
  - `Product::getAverageRatingAttribute()` & `reviews()->count()` dipanggil berulang; pertimbangkan `withCount` / eager load + caching ringan.

- [x] **#12 `.env`**
  - Ganti `MAIL_PASSWORD` (app password Gmail sudah terekspos) dengan credential baru.
  - Set `APP_DEBUG=false` untuk environment production.

- [x] **#13 README.md**
  - README masih template bawaan Laravel. Ganti dengan dokumentasi proyek (ringkas PRD + cara setup).

## ⚙️ Verifikasi Akhir (Definisi Selesai)

- [x] `php -l` semua file PHP yang diubah → tanpa error.
- [x] `php artisan route:list` valid & middleware role tampil di panel.
- [x] `php artisan migrate:fresh --seed` berhasil.
- [x] `npm run build` sukses.
- [x] `composer test` hijau (atau ditulis test baru).


1. Produk toko non-verifikasi tampil di katalog web — ProductController@index/show hanya filter is_active produk (ProductController.php:13), tidak filter toko is_verified/is_active. Padahal API sudah benar via whereHas('store'). Inkon sisten.
2. Race condition stok checkout — cek stok OrderController.php:64 tanpa lockForUpdate(); 2 checkout paralel bisa oversell.
3. Keranjang tidak cek stok saat update/tambah qty — CartController::add (:53) menambah qty tanpa cek total vs stok; error baru ketahuan di checkout.
Keamanan/otorisasi:
4. Verifikasi email tidak di-enforce — panel UMKM/admin hanya auth+role, tanpa middleware verified.
Moderasi:
5. Produk baru langsung aktif — Umkm\ProductController.php:109 set is_active=true saat create → langsung tampil publik meski toko belum terverifikasi.
Kualitas/testing:
6. Tes otomatis hampir kosong — hanya 2 ExampleTest. Belum ada tes untuk checkout, role middleware, filter publik, CRUD.
7. Seeder minim — tidak ada akun customer, ulasan contoh, atau 2+ toko untuk uji lintas toko.
Opsional (catatan, bukan bug):
8. Pembayaran masih manual WhatsApp (sesuai scope PRD).
9. Order::generateCode() pakai uniqid — risiko collision kecil.
10. Ulasan tidak bisa diedit/dihapus admin (hanya approve/tolak).