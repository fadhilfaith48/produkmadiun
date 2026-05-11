<?php
 
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
 
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
 
        // ---- User UMKM Contoh ----
        $umkmUser = \App\Models\User::create([
            'name'     => 'Budi Santoso',
            'email'    => 'umkm@produkmadiun.id',
            'password' => Hash::make('password123'),
            'role'     => 'umkm',
            'phone'    => '082112345678',
        ]);
 
        // ---- Toko UMKM Contoh ----
        $store = \App\Models\Store::create([
            'user_id'     => $umkmUser->id,
            'store_name'  => 'Kripik Tempe Bu Sari',
            'slug'        => 'kripik-tempe-bu-sari',
            'description' => 'Produksi kripik tempe dan camilan tradisional khas Madiun sejak 1995.',
            'address'     => 'Jl. Mawar No. 12, Caruban',
            'village'     => 'Mejayan',
            'district'    => 'Mejayan',
            'phone'       => '082112345678',
            'whatsapp'    => '082112345678',
            'is_verified' => true,
        ]);
 
        // ---- Produk Contoh ----
        $products = [
            [
                'name'        => 'Kripik Tempe Original 250gr',
                'slug'        => 'kripik-tempe-original-250gr',
                'description' => 'Kripik tempe renyah dan gurih, dibuat dari kedelai pilihan tanpa pengawet.',
                'price'       => 18000,
                'stock'       => 100,
                'unit'        => 'bungkus',
                'weight'      => 250,
                'category_id' => 1,
            ],
            [
                'name'        => 'Kripik Tempe Pedas 250gr',
                'slug'        => 'kripik-tempe-pedas-250gr',
                'description' => 'Kripik tempe rasa pedas level 3, cocok untuk oleh-oleh.',
                'price'       => 20000,
                'stock'       => 80,
                'unit'        => 'bungkus',
                'weight'      => 250,
                'category_id' => 1,
            ],
        ];
 
        foreach ($products as $p) {
            $store->products()->create($p);
        }
 
        // ---- Banner ----
        \App\Models\Banner::create([
            'title'     => 'Produk UMKM Terbaik Kabupaten Madiun',
            'image'     => 'banners/default.jpg',
            'is_active' => true,
            'order'     => 1,
        ]);
 
        $this->command->info('Seeder selesai! Login dengan:');
        $this->command->info('Admin: admin@produkmadiun.id / password123');
        $this->command->info('UMKM : umkm@produkmadiun.id / password123');
    }
}