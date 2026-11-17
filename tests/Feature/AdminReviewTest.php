<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class AdminReviewTest extends TestCase
{
    use RefreshDatabase;

    private function makeAdmin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    private function makeReview(bool $approved = false): Review
    {
        $category = Category::create(['name' => 'Kuliner', 'slug' => 'kuliner-' . Str::random(6)]);
        $user     = User::factory()->create(['role' => 'umkm']);
        $store    = Store::create([
            'user_id'     => $user->id,
            'store_name'  => 'Toko Uji',
            'slug'        => 'toko-uji-' . Str::random(6),
            'whatsapp'    => '081234567890',
            'is_verified' => true,
            'is_active'   => true,
        ]);

        $product = Product::create([
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

        return Review::create([
            'product_id'    => $product->id,
            'reviewer_name' => 'Tamu Uji',
            'rating'        => 5,
            'comment'       => 'Ulasan uji.',
            'is_approved'   => $approved,
        ]);
    }

    public function test_daftar_ulasan_dapat_dirender(): void
    {
        $review = $this->makeReview(true);

        $this->actingAs($this->makeAdmin())
            ->get(route('admin.reviews'))
            ->assertOk()
            ->assertSee('Ulasan uji.')
            ->assertSee(route('admin.reviews.edit', $review->id));
    }

    public function test_halaman_edit_ulasan_dapat_dirender(): void
    {
        $review = $this->makeReview();

        $this->actingAs($this->makeAdmin())
            ->get(route('admin.reviews.edit', $review->id))
            ->assertOk()
            ->assertSee('Ulasan uji.');
    }

    public function test_ulasan_dapat_diperbarui(): void
    {
        $review = $this->makeReview();

        $this->actingAs($this->makeAdmin())
            ->put(route('admin.reviews.update', $review->id), [
                'rating'  => 4,
                'comment' => 'Komentar baru.',
            ])
            ->assertSessionHas('success');

        $this->assertSame(4, $review->fresh()->rating);
        $this->assertSame('Komentar baru.', $review->fresh()->comment);
    }

    public function test_ulasan_dapat_dihapus(): void
    {
        $review = $this->makeReview();

        $this->actingAs($this->makeAdmin())
            ->delete(route('admin.reviews.delete', $review->id))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('reviews', ['id' => $review->id]);
    }

    public function test_ulasan_dapat_disetujui_dan_disembunyikan(): void
    {
        $review = $this->makeReview();

        $this->actingAs($this->makeAdmin())
            ->put(route('admin.reviews.approve', $review->id));

        $this->assertTrue((bool) $review->fresh()->is_approved);

        $this->actingAs($this->makeAdmin())
            ->put(route('admin.reviews.reject', $review->id));

        $this->assertFalse((bool) $review->fresh()->is_approved);
    }

    public function test_customer_tidak_bisa_mengelola_ulasan(): void
    {
        $review = $this->makeReview();

        $this->actingAs(User::factory()->create(['role' => 'customer']))
            ->get(route('admin.reviews'))
            ->assertForbidden();

        $this->actingAs(User::factory()->create(['role' => 'customer']))
            ->put(route('admin.reviews.update', $review->id), ['rating' => 1])
            ->assertForbidden();
    }
}
