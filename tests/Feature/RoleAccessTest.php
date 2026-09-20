<?php

namespace Tests\Feature;

use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_di_redirect_ke_login_saat_akses_panel_umkm(): void
    {
        $this->get(route('umkm.dashboard'))
            ->assertRedirect(route('login'));
    }

    public function test_guest_di_redirect_ke_login_saat_akses_panel_admin(): void
    {
        $this->get(route('admin.dashboard'))
            ->assertRedirect(route('login'));
    }

    public function test_customer_tidak_bisa_akses_panel_umkm(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);

        $this->actingAs($customer)
            ->get(route('umkm.dashboard'))
            ->assertForbidden();
    }

    public function test_customer_tidak_bisa_akses_panel_admin(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);

        $this->actingAs($customer)
            ->get(route('admin.dashboard'))
            ->assertForbidden();
    }

    public function test_umkm_tidak_bisa_akses_panel_admin(): void
    {
        $umkm = User::factory()->create(['role' => 'umkm']);

        $this->actingAs($umkm)
            ->get(route('admin.dashboard'))
            ->assertForbidden();
    }

    public function test_admin_tidak_bisa_akses_panel_umkm(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get(route('umkm.dashboard'))
            ->assertForbidden();
    }

    public function test_umkm_belum_verifikasi_email_di_arahkan_ke_notice(): void
    {
        $umkm = User::factory()->create(['role' => 'umkm', 'email_verified_at' => null]);

        $this->actingAs($umkm)
            ->get(route('umkm.dashboard'))
            ->assertRedirect(route('verification.notice'));
    }

    public function test_umkm_terverifikasi_dan_berstatus_aktif_bisa_akses_dashboard(): void
    {
        $umkm = User::factory()->create(['role' => 'umkm']);
        Store::create([
            'user_id'    => $umkm->id,
            'store_name' => 'Toko Uji',
            'slug'       => 'toko-uji-' . Str::random(6),
            'is_verified'=> true,
            'is_active'  => true,
        ]);

        $this->actingAs($umkm)
            ->get(route('umkm.dashboard'))
            ->assertOk();
    }

    public function test_admin_terverifikasi_bisa_akses_dashboard_admin(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk();
    }
}