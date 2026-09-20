<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

    private function makeActiveProduct(): Product
    {
        $category = Category::create(['name' => 'Kuliner', 'slug' => 'kuliner', 'icon' => '🍱']);
        $user     = User::factory()->create(['role' => 'umkm']);
        $store    = Store::create([
            'user_id'    => $user->id,
            'store_name' => 'Toko Uji',
            'slug'       => 'toko-uji-' . Str::random(6),
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
            'price'       => 10000,
            'stock'       => 5,
            'unit'        => 'pcs',
            'is_active'   => true,
        ]);
    }

    public function test_ulasan_pengunjung_tersimpan_dengan_status_belum_disetujui(): void
    {
        $product = $this->makeActiveProduct();

        $this->post(route('reviews.store'), [
            'product_id'    => $product->id,
            'reviewer_name' => 'Andi',
            'rating'        => 5,
            'comment'       => 'Enak sekali!',
        ])->assertSessionHas('success');

        $review = \App\Models\Review::where('product_id', $product->id)->first();
        $this->assertNotNull($review);
        $this->assertFalse((bool) $review->is_approved);
        $this->assertSame('Andi', $review->reviewer_name);
    }

    public function test_ulasan_belum_disetujui_tidak_tampil_di_halaman_produk(): void
    {
        $product = $this->makeActiveProduct();

        \App\Models\Review::create([
            'product_id'    => $product->id,
            'user_id'       => null,
            'reviewer_name' => 'Budi',
            'rating'        => 4,
            'comment'       => 'Komentar belum disetujui.',
            'is_approved'   => false,
        ]);

        $this->get(route('products.show', $product->slug))
            ->assertOk()
            ->assertDontSee('Komentar belum disetujui.');
    }

    public function test_ulasan_yang_disetujui_tampil_di_halaman_produk(): void
    {
        $product = $this->makeActiveProduct();

        \App\Models\Review::create([
            'product_id'    => $product->id,
            'user_id'       => null,
            'reviewer_name' => 'Budi',
            'rating'        => 4,
            'comment'       => 'Komentar sudah disetujui.',
            'is_approved'   => true,
        ]);

        $this->get(route('products.show', $product->slug))
            ->assertOk()
            ->assertSee('Komentar sudah disetujui.');
    }

    public function test_ulasan_untuk_produk_nonaktif_404(): void
    {
        $product = $this->makeActiveProduct();
        $product->update(['is_active' => false]);

        $this->post(route('reviews.store'), [
            'product_id'    => $product->id,
            'reviewer_name' => 'Andi',
            'rating'        => 5,
        ])->assertStatus(404);
    }
}