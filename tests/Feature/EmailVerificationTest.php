<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    private function registerPayload(string $role, string $email): array
    {
        return [
            'name'                  => 'Pengguna Baru',
            'email'                 => $email,
            'password'              => 'password123',
            'password_confirmation' => 'password123',
            'role'                  => $role,
        ];
    }

    public function test_registrasi_mengirim_email_verifikasi(): void
    {
        Notification::fake();

        $this->post(route('register'), $this->registerPayload('umkm', 'baru@example.test'));

        $user = User::where('email', 'baru@example.test')->first();
        $this->assertNotNull($user);
        $this->assertNull($user->email_verified_at);
        Notification::assertSentTo($user, VerifyEmail::class);
    }

    public function test_umkm_baru_di_arahkan_ke_notice_saat_akses_panel(): void
    {
        Notification::fake();

        $this->post(route('register'), $this->registerPayload('umkm', 'umkmbaru@example.test'))
            ->assertRedirect(route('umkm.dashboard'));

        $this->get(route('umkm.dashboard'))
            ->assertRedirect(route('verification.notice'));
    }

    public function test_customer_daftar_langsung_ke_beranda(): void
    {
        Notification::fake();

        $this->post(route('register'), $this->registerPayload('customer', 'cust@example.test'))
            ->assertRedirect('/');
    }

    public function test_link_verifikasi_mengaktifkan_email_user(): void
    {
        $user = User::factory()->create(['role' => 'umkm', 'email_verified_at' => null]);

        $url = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        $this->actingAs($user)->get($url)->assertRedirect();

        $this->assertNotNull($user->fresh()->email_verified_at);
    }

    public function test_registrasi_dengan_email_duplikat_ditolak(): void
    {
        Notification::fake();

        $this->post(route('register'), $this->registerPayload('customer', 'duplikat@example.test'));
        $this->post(route('register'), $this->registerPayload('umkm', 'duplikat@example.test'))
            ->assertSessionHasErrors('email');

        $this->assertSame(1, User::where('email', 'duplikat@example.test')->count());
    }
}