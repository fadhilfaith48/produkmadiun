<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class PublicListingTest extends TestCase
{
    use RefreshDatabase;

    private function makeCategory(): Category
    {
        return Category::create(['name' => 'Kuliner', 'slug' => 'kuliner', 'icon' => '🍱']);
    }

    private function makeStore(User $user, array $overrides = []): Store
    {
        return Store::create(array_merge([
            'user_id'    => $user->id,
            'store_name' => 'Toko Uji',
            'slug'       => Str::slug('Toko Uji') . '-' . $user->id,
            'whatsapp'   => '081234567890',
            'is_verified'=> true,
            'is_active'  => true,
        ], $overrides));
    }

    private function makeProduct(Store $store, Category $category, array $overrides = []): Product
    {
        return Product::create(array_merge([
            'store_id'    => $store->id,
            'category_id' => $category->id,
            'name'        => 'Produk Uji',
            'slug'        => Str::slug('Produk Uji') . '-' . Str::random(6),
            'description' => 'Deskripsi produk uji.',
            'price'       => 10000,
            'stock'       => 10,
            'unit'        => 'pcs',
            'is_active'   => true,
        ], $overrides));
    }

    public function test_catalog_menampilkan_produk_toko_terverifikasi(): void
    {
        $category = $this->makeCategory();
        $store    = $this->makeStore(User::factory()->create(['role' => 'umkm']));
        $product  = $this->makeProduct($store, $category);

        $this->get(route('products.index'))
            ->assertOk()
            ->assertSee($product->name);
    }

    public function test_catalog_menyembunyikan_produk_toko_belum_terverifikasi(): void
    {
        $category = $this->makeCategory();
        $store    = $this->makeStore(User::factory()->create(['role' => 'umkm']), ['is_verified' => false]);
        $product  = $this->makeProduct($store, $category);

        $this->get(route('products.index'))
            ->assertOk()
            ->assertDontSee($product->name);
    }

    public function test_catalog_menyembunyikan_produk_toko_nonaktif(): void
    {
        $category = $this->makeCategory();
        $store    = $this->makeStore(User::factory()->create(['role' => 'umkm']), ['is_active' => false]);
        $product  = $this->makeProduct($store, $category);

        $this->get(route('products.index'))
            ->assertOk()
            ->assertDontSee($product->name);
    }

    public function test_catalog_menyembunyikan_produk_nonaktif(): void
    {
        $category = $this->makeCategory();
        $store    = $this->makeStore(User::factory()->create(['role' => 'umkm']));
        $product  = $this->makeProduct($store, $category, ['is_active' => false]);

        $this->get(route('products.index'))
            ->assertOk()
            ->assertDontSee($product->name);
    }

    public function test_detail_produk_tersembunyi_menghasilkan_404(): void
    {
        $category = $this->makeCategory();
        $store    = $this->makeStore(User::factory()->create(['role' => 'umkm']), ['is_verified' => false]);
        $product  = $this->makeProduct($store, $category);

        $this->get(route('products.show', $product->slug))
            ->assertStatus(404);
    }

public function test_direktori_toko_hanya_menampilkan_toko_terverifikasi(): void
    {
        $verified = $this->makeStore(User::factory()->create(['role' => 'umkm']), ['store_name' => 'Toko Tervalidasi']);
        $notVerified = $this->makeStore(User::factory()->create(['role' => 'umkm']), ['store_name' => 'Toko Belum Verifikasi', 'is_verified' => false]);

        $this->get(route('stores.index'))
            ->assertOk()
            ->assertSee('Toko Tervalidasi')
            ->assertDontSee('Toko Belum Verifikasi');
    }

    public function test_api_publik_produk_menyembunyikan_produk_toko_nonaktif(): void
    {
        $category = $this->makeCategory();
        $activeStore   = $this->makeStore(User::factory()->create(['role' => 'umkm']));
        $inactiveStore = $this->makeStore(User::factory()->create(['role' => 'umkm']), ['is_active' => false]);

        $visible   = $this->makeProduct($activeStore, $category);
        $hidden    = $this->makeProduct($inactiveStore, $category);

        $response = $this->getJson('/api/publik/produk')->assertOk();
        $data = json_decode($response->getContent(), true)['data']['data'];

        $ids = array_column($data, 'id');
        $this->assertContains($visible->id, $ids);
        $this->assertNotContains($hidden->id, $ids);
    }
}