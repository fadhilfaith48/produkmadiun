<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    private function makeStoreProduct(User $user, string $suffix): Product
    {
        $category = Category::create(['name' => 'Kategori ' . $suffix, 'slug' => 'kategori-' . Str::slug($suffix), 'icon' => '🍱']);
        $store    = Store::create([
            'user_id'    => $user->id,
            'store_name' => 'Toko ' . $suffix,
            'slug'       => 'toko-' . Str::slug($suffix) . '-' . $user->id,
            'whatsapp'   => '081234567890',
            'is_verified'=> true,
            'is_active'  => true,
        ]);

        return Product::create([
            'store_id'    => $store->id,
            'category_id' => $category->id,
            'name'        => 'Produk ' . $suffix,
            'slug'        => 'produk-' . Str::slug($suffix),
            'description' => 'Deskripsi ' . $suffix . '.',
            'price'       => 10000,
            'stock'       => 5,
            'unit'        => 'pcs',
            'is_active'   => true,
        ]);
    }

    private function payload(array $overrides = []): array
    {
        return array_merge([
            'customer_name'     => 'Andi Pembeli',
            'customer_phone'    => '081234567890',
            'customer_whatsapp' => '081234567890',
            'customer_address'  => 'Jl. Melati No. 1, Caruban',
            'payment_method'    => 'whatsapp',
            'notes'             => null,
        ], $overrides);
    }

    public function test_checkout_lintas_toko_membuat_satu_pesanan_per_toko(): void
    {
        $productA = $this->makeStoreProduct(User::factory()->create(['role' => 'umkm']), 'A');
        $productB = $this->makeStoreProduct(User::factory()->create(['role' => 'umkm']), 'B');

        session()->put('cart', [
            (string) $productA->id => ['qty' => 2, 'store_id' => $productA->store_id],
            (string) $productB->id => ['qty' => 1, 'store_id' => $productB->store_id],
        ]);

        $response = $this->post(route('order.store'), $this->payload());
        $response->assertRedirect(route('order.success', Order::first()->order_code));

        $orders = Order::get();
        $this->assertCount(2, $orders);
        $this->assertEqualsCanonicalizing(
            [$productA->store_id, $productB->store_id],
            $orders->pluck('store_id')->all()
        );
        $this->assertEmpty(session('cart', []));
    }

    public function test_stok_berkurang_setelah_checkout(): void
    {
        $product = $this->makeStoreProduct(User::factory()->create(['role' => 'umkm']), 'A');

        session()->put('cart', [(string) $product->id => ['qty' => 3, 'store_id' => $product->store_id]]);

        $this->post(route('order.store'), $this->payload())->assertRedirect();

        $this->assertSame(2, $product->fresh()->stock);
        $this->assertSame(3, Order::first()->items()->sum('quantity'));
    }

    public function test_checkout_produk_nonaktif_ditolak_tanpa_membuat_pesanan(): void
    {
        $product = $this->makeStoreProduct(User::factory()->create(['role' => 'umkm']), 'A');
        $product->update(['is_active' => false]);

        session()->put('cart', [(string) $product->id => ['qty' => 1, 'store_id' => $product->store_id]]);

        $this->post(route('order.store'), $this->payload())
            ->assertRedirect()
            ->assertSessionHasErrors('product');

        $this->assertSame(0, Order::count());
        $this->assertNotEmpty(session('cart', []));
    }

    public function test_checkout_stok_tidak_mencukupi_ditolak_tanpa_mengurangi_stok(): void
    {
        $product = $this->makeStoreProduct(User::factory()->create(['role' => 'umkm']), 'A');
        $product->update(['stock' => 1]);

        session()->put('cart', [(string) $product->id => ['qty' => 2, 'store_id' => $product->store_id]]);

        $this->post(route('order.store'), $this->payload())
            ->assertRedirect()
            ->assertSessionHasErrors('product');

        $this->assertSame(0, Order::count());
        $this->assertSame(1, $product->fresh()->stock);
    }
}