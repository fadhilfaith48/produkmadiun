<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ---- Admin ----
        \App\Models\User::create([
            'name'     => 'Admin ProdukMadiun',
            'email'    => 'admin@produkmadiun.id',
            'password' => Hash::make('password123'),
            'role'     => 'admin',
            'phone'    => '081234567890',
            'email_verified_at' => now(),
        ]);

        // ---- Kategori ----
        $categories = [
            ['name' => 'Makanan & Minuman', 'slug' => 'makanan-minuman',   'icon' => '🍱'],
            ['name' => 'Kerajinan',          'slug' => 'kerajinan',         'icon' => '🎨'],
            ['name' => 'Pertanian',          'slug' => 'pertanian',         'icon' => '🌾'],
            ['name' => 'Fashion',            'slug' => 'fashion',           'icon' => '👗'],
            ['name' => 'Elektronik Lokal',   'slug' => 'elektronik-lokal',  'icon' => '🔌'],
            ['name' => 'Jasa',               'slug' => 'jasa',              'icon' => '🛠️'],
        ];

        foreach ($categories as $cat) {
            \App\Models\Category::create($cat);
        }

        $catMakanan = \App\Models\Category::where('slug', 'makanan-minuman')->first();
        $catKerajinan = \App\Models\Category::where('slug', 'kerajinan')->first();
        $catPertanian = \App\Models\Category::where('slug', 'pertanian')->first();

        // ---- Customer ----
        $customer = \App\Models\User::create([
            'name'     => 'Andi Pembeli',
            'email'    => 'customer@produkmadiun.id',
            'password' => Hash::make('password123'),
            'role'     => 'customer',
            'phone'    => '085712345678',
            'email_verified_at' => now(),
        ]);

        // ---- UMKM 1: Kripik Tempe Bu Sari (terverifikasi & aktif) ----
        $budi = \App\Models\User::create([
            'name'     => 'Budi Santoso',
            'email'    => 'umkm@produkmadiun.id',
            'password' => Hash::make('password123'),
            'role'     => 'umkm',
            'phone'    => '082112345678',
            'email_verified_at' => now(),
        ]);

        $storeSari = \App\Models\Store::create([
            'user_id'     => $budi->id,
            'store_name'  => 'Kripik Tempe Bu Sari',
            'slug'        => 'kripik-tempe-bu-sari',
            'description' => 'Produksi kripik tempe dan camilan tradisional khas Madiun sejak 1995.',
            'address'     => 'Jl. Mawar No. 12, Caruban',
            'village'     => 'Mejayan',
            'district'    => 'Mejayan',
            'phone'       => '082112345678',
            'whatsapp'    => '082112345678',
            'is_verified' => true,
            'is_active'   => true,
        ]);

        $kripikOriginal = $storeSari->products()->create([
            'name'        => 'Kripik Tempe Original 250gr',
            'slug'        => 'kripik-tempe-original-250gr',
            'description' => 'Kripik tempe renyah dan gurih, dibuat dari kedelai pilihan tanpa pengawet.',
            'price'       => 18000,
            'stock'       => 100,
            'unit'        => 'bungkus',
            'weight'      => 250,
            'category_id' => $catMakanan->id,
            'is_active'   => true,
            'views'       => 320,
        ]);

        $kripikPedas = $storeSari->products()->create([
            'name'        => 'Kripik Tempe Pedas 250gr',
            'slug'        => 'kripik-tempe-pedas-250gr',
            'description' => 'Kripik tempe rasa pedas level 3, cocok untuk oleh-oleh.',
            'price'       => 20000,
            'stock'       => 80,
            'unit'        => 'bungkus',
            'weight'      => 250,
            'category_id' => $catMakanan->id,
            'is_active'   => true,
            'views'       => 260,
        ]);

        $storeSari->products()->create([
            'name'        => 'Stik Keju Gurih 200gr',
            'slug'        => 'stik-keju-gurih-200gr',
            'description' => 'Stik keju renyah hasil oven, tanpa pengawet.',
            'price'       => 15000,
            'stock'       => 60,
            'unit'        => 'bungkus',
            'weight'      => 200,
            'category_id' => $catMakanan->id,
            'is_active'   => false,
            'views'       => 0,
        ]);

        // ---- UMKM 2: Bathik Sekar Arum (belum terverifikasi) ----
        $siti = \App\Models\User::create([
            'name'     => 'Siti Aminah',
            'email'    => 'umkm2@produkmadiun.id',
            'password' => Hash::make('password123'),
            'role'     => 'umkm',
            'phone'    => '082234567890',
            'email_verified_at' => now(),
        ]);

        $storeBathik = \App\Models\Store::create([
            'user_id'     => $siti->id,
            'store_name'  => 'Bathik Sekar Arum',
            'slug'        => 'bathik-sekar-arum',
            'description' => 'Batik tulis khas Madiun dikerjakan pengrajin lokal.',
            'address'     => 'Jl. Kenanga No. 7, Saradan',
            'village'     => 'Sugihwaras',
            'district'    => 'Saradan',
            'phone'       => '082234567890',
            'whatsapp'    => '082234567890',
            'is_verified' => false,
            'is_active'   => true,
        ]);

        $storeBathik->products()->create([
            'name'        => 'Kain Batik Tulis Sekar Arum',
            'slug'        => 'kain-batik-tulis-sekar-arum',
            'description' => 'Batik tulis handmade, pewarna alami, bahan katun primisima.',
            'price'       => 350000,
            'stock'       => 15,
            'unit'        => 'potong',
            'weight'      => 500,
            'category_id' => $catKerajinan->id,
            'is_active'   => true,
        ]);

        $storeBathik->products()->create([
            'name'        => 'Gantungan Kunci Batik',
            'slug'        => 'gantungan-kunci-batik',
            'description' => 'Gantungan kunci motif batik khas Madiun, cocok untuk suvenir.',
            'price'       => 20000,
            'stock'       => 200,
            'unit'        => 'pcs',
            'weight'      => 50,
            'category_id' => $catKerajinan->id,
            'is_active'   => true,
        ]);

        $storeBathik->products()->create([
            'name'        => 'Dompet Batik Kecil',
            'slug'        => 'dompet-batik-kecil',
            'description' => 'Dompet batik kombinasi kulit sintetis, praktis dan bergaya.',
            'price'       => 45000,
            'stock'       => 50,
            'unit'        => 'pcs',
            'weight'      => 100,
            'category_id' => $catKerajinan->id,
            'is_active'   => true,
        ]);

        // ---- UMKM 3: Sayur Segar Madiun (terverifikasi & aktif) ----
        $dewi = \App\Models\User::create([
            'name'     => 'Dewi Lestari',
            'email'    => 'umkm3@produkmadiun.id',
            'password' => Hash::make('password123'),
            'role'     => 'umkm',
            'phone'    => '082356789012',
            'email_verified_at' => now(),
        ]);

        $storeSayur = \App\Models\Store::create([
            'user_id'     => $dewi->id,
            'store_name'  => 'Sayur Segar Madiun',
            'slug'        => 'sayur-segar-madiun',
            'description' => 'Hasil pertanian segar dari petani binaan Kabupaten Madiun.',
            'address'     => 'Jl. Flamboyan No. 21, Balerejo',
            'village'     => 'Bulu',
            'district'    => 'Balerejo',
            'phone'       => '082356789012',
            'whatsapp'    => '082356789012',
            'is_verified' => true,
            'is_active'   => true,
        ]);

        $berasMerah = $storeSayur->products()->create([
            'name'        => 'Beras Merah Organik 1kg',
            'slug'        => 'beras-merah-organik-1kg',
            'description' => 'Beras merah organik hasil panen segar, bebas pestisida.',
            'price'       => 22000,
            'stock'       => 120,
            'unit'        => 'kg',
            'weight'      => 1000,
            'category_id' => $catPertanian->id,
            'is_active'   => true,
            'views'       => 180,
        ]);

        $storeSayur->products()->create([
            'name'        => 'Ubi Cilembu Super 1kg',
            'slug'        => 'ubi-cilembu-super-1kg',
            'description' => 'Ubi cilembu madu, manis dan legit.',
            'price'       => 18000,
            'stock'       => 90,
            'unit'        => 'kg',
            'weight'      => 1000,
            'category_id' => $catPertanian->id,
            'is_active'   => true,
            'views'       => 140,
        ]);

        $storeSayur->products()->create([
            'name'        => 'Madu Murni Hutan 250ml',
            'slug'        => 'madu-murni-hutan-250ml',
            'description' => 'Madu murni dari hutan jati Madiun, belum dipasteurisasi.',
            'price'       => 85000,
            'stock'       => 40,
            'unit'        => 'botol',
            'weight'      => 350,
            'category_id' => $catPertanian->id,
            'is_active'   => false,
            'views'       => 0,
        ]);

        // ---- Banner ----
        \App\Models\Banner::create([
            'title'     => 'Produk UMKM Terbaik Kabupaten Madiun',
            'image'     => 'banners/default.jpg',
            'is_active' => true,
            'order'     => 1,
        ]);

        \App\Models\Banner::create([
            'title'     => 'Dukung UMKM Lokal Madiun',
            'image'     => 'banners/default.jpg',
            'is_active' => true,
            'order'     => 2,
        ]);

        \App\Models\Banner::create([
            'title'     => 'Promo Belanja UMKM',
            'image'     => 'banners/default.jpg',
            'is_active' => false,
            'order'     => 3,
        ]);

        // ---- Ulasan ----
        // Approved & pending dari customer login
        \App\Models\Review::create([
            'product_id'    => $kripikOriginal->id,
            'user_id'       => $customer->id,
            'reviewer_name' => $customer->name,
            'rating'        => 5,
            'comment'       => 'Kripiknya renyah dan gurih, sesuai ekspektasi!',
            'is_approved'   => true,
        ]);

        // Approved dari tamu
        \App\Models\Review::create([
            'product_id'    => $kripikOriginal->id,
            'user_id'       => null,
            'reviewer_name' => 'Rina Purnamasari',
            'rating'        => 4,
            'comment'       => 'Enak, cocok untuk oleh-oleh dari Madiun.',
            'is_approved'   => true,
        ]);

        // Pending dari customer login
        \App\Models\Review::create([
            'product_id'    => $kripikOriginal->id,
            'user_id'       => $customer->id,
            'reviewer_name' => $customer->name,
            'rating'        => 3,
            'comment'       => 'Rasa oke tapi pengemasan kurang rapi.',
            'is_approved'   => false,
        ]);

        // Pending dari tamu
        \App\Models\Review::create([
            'product_id'    => $kripikPedas->id,
            'user_id'       => null,
            'reviewer_name' => 'Bagas Adi',
            'rating'        => 5,
            'comment'       => 'Pedasnya pas buat penggemar pedas!',
            'is_approved'   => false,
        ]);

        // Approved dari customer login
        \App\Models\Review::create([
            'product_id'    => $kripikPedas->id,
            'user_id'       => $customer->id,
            'reviewer_name' => $customer->name,
            'rating'        => 4,
            'comment'       => 'Level pedasnya enak, pengiriman cepat.',
            'is_approved'   => true,
        ]);

        \App\Models\Review::create([
            'product_id'    => $berasMerah->id,
            'user_id'       => $customer->id,
            'reviewer_name' => $customer->name,
            'rating'        => 5,
            'comment'       => 'Beras merahnya wangi dan bersih.',
            'is_approved'   => true,
        ]);

        $this->command->info('Seeder selesai! Login dengan (password: password123):');
        $this->command->info('Admin    : admin@produkmadiun.id');
        $this->command->info('UMKM 1   : umkm@produkmadiun.id (Kripik Tempe Bu Sari — verifikasi)');
        $this->command->info('UMKM 2   : umkm2@produkmadiun.id (Bathik Sekar Arum — BELUM verifikasi)');
        $this->command->info('UMKM 3   : umkm3@produkmadiun.id (Sayur Segar Madiun — verifikasi)');
        $this->command->info('Customer : customer@produkmadiun.id');
    }
}