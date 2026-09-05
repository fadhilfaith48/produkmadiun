# Uji Manual — ProdukMadiun

Checklist uji manual end-to-end. Centang `[x]` saat sudah teruji & lolos.

**Persiapan sebelum menguji:**
```bash
composer install
npm install && npm run dev        # atau npm run build
php artisan migrate:fresh --seed  # reset DB + data contoh
php artisan serve                 # buka http://127.0.0.1:8000
```

**Akun contoh hasil seed:**
| Role   | Email                     | Password    |
|--------|---------------------------|-------------|
| Admin  | admin@produkmadiun.id     | password123 |
| UMKM   | umkm@produkmadiun.id      | password123 |

---

## 1. Halaman Publik

### 1.1 Home
- [x] `/` tampil tanpa error, hero/banner dari tabel `banners` terlihat.
- [x] Kategori, produk unggulan/terbaru, dan toko terverifikasi tampil.
- [x] Banner non-aktif (`is_active=false`) tidak tampil.

### 1.2 Katalog Produk
- [x] `/katalog` menampilkan semua produk dengan `is_active=true`.
- [x] Pencarian nama produk berfungsi.
- [x] Filter kategori & sort harga/popularitas berfungsi.
- [x] Produk dengan `is_active=false` TIDAK tampil di katalog.

### 1.3 Detail Produk
- [x] `/katalog/{slug}` tampil dengan galeri, harga, stok, deskripsi.
- [x] Tombol "Beli" menambah produk ke keranjang.
- [x] Tombol WhatsApp langsung ke chat toko (format `wa.me` benar).
- [x] Form ulasan valid: rating 1–5 wajib, komentar opsional.
- [x] Setelah submit ulasan → pesan "menunggu persetujuan admin", ulasan belum tampil.
- [x] Ulasan yang sudah `is_approved=true` tampil beserta rating rata-rata.
- [x] Ulasan untuk produk non-aktif menampilkan 404.

### 1.4 Direktori Toko
- [x] `/toko` hanya menampilkan toko `is_verified=true` DAN `is_active=true`.
- [x] Toko non-aktif atau belum terverifikasi TIDAK tampil.
- [x] `/toko/{slug}` menampilkan detail, produk, kontak, WhatsApp.

### 1.5 Keranjang
- [x] Tambah produk → jumlah & total di keranjang benar.
- [x] Update qty → subtotal ikut berubah.
- [x] Hapus item & kosongkan keranjang berfungsi.
- [x] Keranjang berisi produk dari 2+ toko berbeda tetap bisa dilanjutkan.

### 1.6 Checkout & Pesanan
- [x] Keranjang kosong → `/pesan/checkout` redirect ke keranjang + pesan error.
- [x] Checkout tanpa login diperbolehkan (guest checkout) jika mengisi data pembeli.
- [x] Validasi: nama, HP, WhatsApp, alamat, metode bayar (`cod|transfer|whatsapp`) wajib benar.
- [x] Submit pesanan lintas toko → terbuat **1 order per toko**, semua kode order tampil di halaman sukses.
- [x] Stok produk berkurang sesuai qty setelah checkout.
- [x] Keranjang terkosongkan setelah checkout berhasil.
- [x] Halaman sukses `/pesan/sukses/{code}` menampilkan seluruh order yang dibuat.
- [x] Tombol WhatsApp konfirmasi → pesan berisi daftar item, total, kode order, format HP `08xx` → `62xx`.
- [x] Produk yang habis stok / tak aktif saat checkout → error jelas, transaksi batal (tidak ada order & stok tak berubah).

### 1.7 Lacak Pesanan
- [x] `/pesan/lacak` dengan kode valid → status pesanan tampil.
- [x] Kode tidak valid → pesan "Kode pesanan tidak ditemukan".

---

## 2. Autentikasi

- [x] Register role `customer` → login berhasil.
- [x] Register role `umkm` → login berhasil.
- [ ] Duplikat email saat register → error validasi.
- [ ] Login salah password → error.
- [ ] Logout → session berakhir.
- [ ] Lupa password: kirim email reset, buka link, set password baru, login dengan password baru.
- [ ] Verifikasi email: user terverifikasi bisa akses penuh; user belum verifikasi diblokir/lihat notice.

---

## 3. Panel UMKM (`/umkm`)

### 3.1 Dashboard & Profil Toko
- [ ] `/umkm/dashboard` menampilkan total produk, pesanan, pendapatan, pesanan terbaru.
- [ ] `/umkm/profil-toko` menampilkan & menyimpan nama, deskripsi, alamat, kecamatan, kontak, logo, banner.
- [ ] Simpan profil toko → `is_active` TIDAK berubah menjadi 0 (toko tetap tampil publik).
- [ ] Ganti nama toko → slug berubah & unik; nama sama dengan toko lain → tidak error SQL (slug diberi akhiran unik).
- [ ] UMKM tanpa toko → diarahkan melengkapi profil toko.

### 3.2 Produk (CRUD + Galeri)
- [ ] Tambah produk (nama, harga, stok, unit, berat, kategori, deskripsi) → tersimpan.
- [ ] Nama produk duplikat slug → otomatis dibuat unik, tidak error.
- [ ] Edit produk → perubahan tersimpan.
- [ ] Upload 1+ foto tambahan per produk → foto muncul di galeri detail produk.
- [ ] Hapus foto galeri → foto hilang dari storage & galeri.
- [ ] Hapus produk → hilang dari daftar & tidak tampil publik.
- [ ] Produk dengan `is_active=false` tidak tampil di katalog publik.

### 3.3 Pesanan
- [ ] `/umkm/pesanan` hanya menampilkan order milik tokonya sendiri.
- [ ] Detail order menampilkan item, qty, subtotal, data pembeli.
- [ ] Update status: `pending → confirmed → processing → shipped → completed / cancelled`.
- [ ] Status yang diubah tercermin di halaman lacak publik.

---

## 4. Panel Admin (`/admin`)

- [ ] `/admin/dashboard` menampilkan statistik platform (produk, toko, pesanan, ulasan).

### 4.1 Kelola Toko
- [ ] `/admin/toko` menampilkan semua toko + status verifikasi.
- [ ] Verifikasi toko → toko tampil di direktori publik.
- [ ] Batalkan verifikasi → toko hilang dari direktori publik.

### 4.2 Kelola Ulasan
- [ ] `/admin/ulasan` menampilkan ulasan belum/telah disetujui.
- [ ] Approve ulasan → tampil di detail produk publik.
- [ ] Tolak ulasan → tidak tampil publik.

### 4.3 Kelola Banner
- [ ] Tambah banner (title, gambar, urutan, status aktif) → tampil di home.
- [ ] Edit banner → perubahan tampil.
- [ ] Non-aktifkan banner → hilang dari home.
- [ ] Hapus banner → hilang.

---

## 5. Keamanan & Otorisasi Role (Kritis)

- [ ] Akses `/admin/*` dengan role `umkm`/`customer` → ditolak (403/redirect).
- [ ] Akses `/umkm/*` dengan role `admin`/`customer` → ditolak.
- [ ] Akses panel tanpa login → redirect ke `/login`.
- [ ] `role:user` (bukan admin/umkm/customer) tidak bisa masuk panel mana pun.
- [ ] Rute API publik `/api/publik/*` bisa diakses tanpa token.

---

## 6. API Publik (`/api/publik/*`)

- [ ] `GET /api/publik/produk` → search, filter kategori & kecamatan, pagination.
- [ ] `GET /api/publik/produk/{id}` → detail + `views` bertambah.
- [ ] `GET /api/publik/umkm` → hanya toko terverifikasi & aktif.
- [ ] `GET /api/publik/umkm/{id}` → detail toko.
- [ ] `GET /api/publik/kategori`, `/kecamatan`, `/statistik` → data valid.

---

## 7. Non-Fungsional

- [ ] `php artisan route:list` valid, semua rute panel memakai middleware `auth` + `role`.
- [ ] `composer test` hijau.
- [ ] `npm run build` sukses tanpa error.
- [ ] `php artisan migrate:fresh --seed` sukses tanpa error.
- [ ] `php -l` bersih di file PHP yang diubah (jalankan lewat `find app -name "*.php" -exec php -l {} \;`).
- [ ] Upload gambar tersimpan di `storage/app/public` dan bisa diakses via `/storage/...`.

---

## Ringkasan Hasil

- [ ] **Semua kasus di atas lolos** → layak lanjut ke tahap deployment.
- [ ] Ada temuan → catat di bawah.

### Catatan Temuan
```
(isi: fitur, langkah reproduksi, hasil yang diharapkan vs aktual)
```
