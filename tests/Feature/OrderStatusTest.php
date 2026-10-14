<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class OrderStatusTest extends TestCase
{
    use RefreshDatabase;

    private function makeStore(User $user): Store
    {
        return Store::create([
            'user_id'    => $user->id,
            'store_name' => 'Toko Uji',
            'slug'       => 'toko-uji-' . Str::random(6),
            'whatsapp'   => '081234567890',
            'is_verified'=> true,
            'is_active'  => true,
        ]);
    }

    private function makeOrder(Store $store, string $status): Order
    {
        return Order::create([
            'store_id'     => $store->id,
            'order_code'   => Order::generateCode(),
            'customer_name'=> 'Andi Pembeli',
            'customer_phone' => '081234567890',
            'customer_whatsapp' => '081234567890',
            'customer_address' => 'Jl. Melati No. 1, Caruban',
            'subtotal'     => 10000,
            'total'        => 10000,
            'status'       => $status,
        ]);
    }

    public function test_transisi_status_yang_valid_berhasil(): void
    {
        $umkm  = User::factory()->create(['role' => 'umkm']);
        $store = $this->makeStore($umkm);
        $order = $this->makeOrder($store, 'pending');

        $this->actingAs($umkm)
            ->put(route('umkm.orders.update-status', $order->id), ['status' => 'confirmed'])
            ->assertRedirect();

        $this->assertSame('confirmed', $order->fresh()->status);
    }

    public function test_transisi_status_yang_tidak_valid_ditolak(): void
    {
        $umkm  = User::factory()->create(['role' => 'umkm']);
        $store = $this->makeStore($umkm);
        $order = $this->makeOrder($store, 'pending');

        $this->actingAs($umkm)
            ->put(route('umkm.orders.update-status', $order->id), ['status' => 'completed'])
            ->assertRedirect()
            ->assertSessionHasErrors('status');

        $this->assertSame('pending', $order->fresh()->status);
    }

    public function test_status_final_tidak_dapat_diubah_lagi(): void
    {
        $umkm  = User::factory()->create(['role' => 'umkm']);
        $store = $this->makeStore($umkm);
        $order = $this->makeOrder($store, 'completed');

        $this->actingAs($umkm)
            ->put(route('umkm.orders.update-status', $order->id), ['status' => 'pending'])
            ->assertRedirect()
            ->assertSessionHasErrors('status');

        $this->assertSame('completed', $order->fresh()->status);
    }

    public function test_halaman_daftar_pesanan_dapat_dirender(): void
    {
        $umkm  = User::factory()->create(['role' => 'umkm']);
        $store = $this->makeStore($umkm);
        $this->makeOrder($store, 'shipped');

        $this->actingAs($umkm)
            ->get(route('umkm.orders.index'))
            ->assertOk()
            ->assertSee('Dikirim');
    }

    public function test_halaman_detail_pesanan_dapat_dirender(): void
    {
        $umkm  = User::factory()->create(['role' => 'umkm']);
        $store = $this->makeStore($umkm);
        $order = $this->makeOrder($store, 'pending');

        $this->actingAs($umkm)
            ->get(route('umkm.orders.show', $order->id))
            ->assertOk()
            ->assertSee($order->order_code);
    }

    public function test_umkm_tidak_dapat_mengubah_pesanan_toko_lain(): void
    {
        $umkm   = User::factory()->create(['role' => 'umkm']);
        $this->makeStore($umkm);

        $lain   = User::factory()->create(['role' => 'umkm']);
        $tokoLain = $this->makeStore($lain);
        $order  = $this->makeOrder($tokoLain, 'pending');

        $this->actingAs($umkm)
            ->put(route('umkm.orders.update-status', $order->id), ['status' => 'confirmed'])
            ->assertNotFound();

        $this->assertSame('pending', $order->fresh()->status);
    }
}