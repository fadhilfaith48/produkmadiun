<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    private function makeVerifiedProduct(int $stock = 5, int $price = 10000): Product
    {
        $category = Category::create(['name' => 'Kuliner', 'slug' => 'kuliner', 'icon' => '🍱']);
        $user     = User::factory()->create(['role' => 'umkm']);
        $store    = Store::create([
            'user_id'    => $user->id,
            'store_name' => 'Toko Uji',
            'slug'       => Str::slug('Toko Uji') . '-' . $user->id,
            'whatsapp'   => '081234567890',
            'is_verified'=> true,
            'is_active'  => true,
        ]);

        return Product::create([
            'store_id'    => $store->id,
            'category_id' => $category->id,
            'name'        => 'Produk Uji',
            'slug'        => 'produk-uji-' . Str::random(6),
            'description' => 'Deskripsi.',
            'price'       => $price,
            'stock'       => $stock,
            'unit'        => 'pcs',
            'is_active'   => true,
        ]);
    }

    public function test_tambah_produk_melebihi_stok_ditolak(): void
    {
        $product = $this->makeVerifiedProduct(stock: 3);

        $this->post(route('cart.add'), ['product_id' => $product->id, 'qty' => 5])
            ->assertRedirect()
            ->assertSessionHas('error');

        $this->assertEmpty(session('cart', []));
    }

    public function test_menambah_qty_ulang_melebihi_stok_ditolak(): void
    {
        $product = $this->makeVerifiedProduct(stock: 4);

        session()->put('cart', [(string) $product->id => ['qty' => 3, 'store_id' => $product->store_id]]);

        $this->post(route('cart.add'), ['product_id' => $product->id, 'qty' => 2])
            ->assertRedirect()
            ->assertSessionHas('error');

        $this->assertEquals(3, session('cart')[$product->id]['qty']);
    }

    public function test_tambah_produk_sesuai_stok_berhasil(): void
    {
        $product = $this->makeVerifiedProduct(stock: 10);

        $this->post(route('cart.add'), ['product_id' => $product->id, 'qty' => 3])
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertEquals(3, session('cart')[$product->id]['qty']);
    }

    public function test_update_qty_melebihi_stok_ditolak(): void
    {
        $product = $this->makeVerifiedProduct(stock: 2);

        session()->put('cart', [(string) $product->id => ['qty' => 1, 'store_id' => $product->store_id]]);

        $this->put(route('cart.update', $product->id), ['qty' => 5])
            ->assertRedirect()
            ->assertSessionHas('error');

        $this->assertEquals(1, session('cart')[$product->id]['qty']);
    }

    public function test_update_qty_valid_berhasil(): void
    {
        $product = $this->makeVerifiedProduct(stock: 10);

        session()->put('cart', [(string) $product->id => ['qty' => 1, 'store_id' => $product->store_id]]);

        $this->put(route('cart.update', $product->id), ['qty' => 7])
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertEquals(7, session('cart')[$product->id]['qty']);
    }
}