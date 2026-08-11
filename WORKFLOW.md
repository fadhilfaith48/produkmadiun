# WORKFLOW — Alur Kerja Pengembangan ProdukMadiun

## 1. Alur Umum (Git Flow Sederhana)

```
main (stable)
  └── feature/<fitur>      # dikerjakan → PR → merge ke main
```

1. `git checkout -b feature/<fitur>` dari `main`.
2. Kerjakan + tulis/update test bila relevan.
3. Jalankan verifikasi lokal (bagian 3).
4. Commit dengan pesan yang jelas (lihat bagian 5).
5. Push & buat Pull Request.
6. Review → merge. Jangan commit langsung ke `main` tanpa diminta.

> Aturan: **jangan commit/push tanpa instruksi eksplisit dari user/PO.**

## 2. Alur Kerja Fitur (Development Loop)

1. **Baca dulu** `AGENTS.md`, `ARCHITECTURE.md`, `TODO.md`.
2. **Cek rute** terkait: `php artisan route:list`.
3. Implementasi **backend dulu** (route → controller → model), baru view Blade.
4. Ikuti pola yang ada (lihat "Pola Penting" di AGENTS.md).
5. Validasi input via `$request->validate()`.
6. Untuk perubahan schema: buat migration baru (jangan edit migration lama jika sudah dipakai).
7. Selalu update `TODO.md` / checklist jika menyelesaikan item.

## 3. Verifikasi Wajib (Sebelum Selesai)

```bash
# 1. Syntax semua file PHP yang diubah
php -l <file1> <file2>

# 2. Rute tetap valid
php artisan route:list

# 3. Jika ada perubahan skema & MySQL aktif
php artisan migrate:status

# 4. Build frontend
npm run build

# 5. Test suite (jika ada)
composer test
```

**Checklist:** lihat `AGENTS.md` → "Checklist Sebelum Selesai".

## 4. Alur Kerja Spesifik Fitur

### 4.1 Menambah Fitur Publik
1. Tambah rute di `routes/web.php` (publik, di atas grup auth).
2. Buat/ubah controller publik (`app/Http/Controllers/*`).
3. Filter `is_active`/`is_verified` sesuai aturan bisnis.
4. Buat view Blade + partial bila perlu (`resources/views/partials/`).

### 4.2 Menambah Fitur Panel UMKM
1. Rute dalam grup `prefix('umkm')` + middleware `auth` (dan nantinya `role:umkm`).
2. Controller di `app/Http/Controllers/Umkm/`, selalu ambil store milik user login (`Auth::user()->store` / helper `getStore()`).
3. Pastikan **authorisasi per data** (mis. `where('store_id', $store->id)`).

### 4.3 Menambah Fitur Panel Admin
1. Rute dalam grup `prefix('admin')` + middleware `auth` (dan nantinya `role:admin`).
2. Controller di `app/Http/Controllers/Admin/`.

### 4.4 Perubahan Checkout / Pesanan
1. Bungkus dalam `DB::transaction`.
2. Validasi ulang stok & keberadaan produk.
3. Jangan ubah format order code tanpa koordinasi (konsumen memakainya untuk lacak).
4. Uji alur: tambah keranjang → checkout → sukses/wa.me → stok berkurang → status pesanan di panel UMKM.

## 5. Konvensi Commit

- Bahasa pesan commit: **Indonesia** (mengikuti repo, mis. `fix: ...`, `feat: ...`, `refactor: ...`).
- Contoh:
  - `fix: tambah namespace DatabaseSeeder`
  - `feat: proteksi panel admin dengan middleware role`
  - `feat: halaman edit produk UMKM`
- Satu commit fokus pada satu perubahan logis.

## 6. Alur Handling Bug (dari audit)

```
Temukan bug → daftarkan di TODO.md (prioritas 🔴/🟠/🟡)
  → buat branch fix/<deskripsi> → perbaiki → verifikasi (bagian 3)
  → update TODO.md (tandai selesai) → commit → PR
```

## 7. Prosedur Rilis

1. Verifikasi lengkap: migrate fresh + seed, build, route:list, test.
2. `APP_ENV=production`, `APP_DEBUG=false`, cache config/rute:
   ```bash
   php artisan config:cache && php artisan route:cache && php artisan view:cache
   ```
3. `npm run build` untuk asset produksi.
4. Backup DB sebelum rilis (mysqldump).
5. Catat versi di changelog/release notes.
