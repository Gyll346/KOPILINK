<?php

namespace Tests\Feature;

use App\Models\CoffeeShop;
use App\Models\KemasanKopi;
use App\Models\Lahan;
use App\Models\Petani;
use App\Models\Produksi;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class SecurityHardeningTest extends TestCase
{
    public function test_csrf_protection_is_active_on_donasi_expire()
    {
        // Tanpa CSRF token, POST /donasi/expire harus ditolak (419)
        $response = $this->post('/donasi/expire', ['order_id' => 'NON-EXISTENT']);
        $response->assertStatus(419);
    }

    public function test_otp_verification_rate_limiting_locks_after_5_failures()
    {
        $this->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class]);

        $token = 'test_token_security_' . uniqid();
        RateLimiter::clear('otp_attempts_' . $token);

        Cache::put('pending_register_' . $token, [
            'username' => 'petani_sec_test',
            'password' => bcrypt('secret123'),
            'nama' => 'Petani Sec',
            'kontak' => '089911223344',
            'kelompok_tani' => 'Pasak Piang',
            'otp_code' => '999999',
            'otp_expired_at' => now()->addMinutes(10),
        ], now()->addMinutes(15));

        // 5 kali salah input OTP
        for ($i = 1; $i <= 5; $i++) {
            $resp = $this->post(route('otp.verify.submit'), [
                'token' => $token,
                'otp_code' => '111111',
            ]);
            $resp->assertSessionHasErrors('otp_code');
        }

        // Percobaan ke-6 harus ditolak oleh RateLimiter
        $lockedResp = $this->post(route('otp.verify.submit'), [
            'token' => $token,
            'otp_code' => '999999', // Meskipun memasukkan kode benar
        ]);
        $lockedResp->assertSessionHasErrors('otp_code');
        $this->assertTrue(str_contains(session('errors')->first('otp_code'), 'Terlalu banyak percobaan'));

        Cache::forget('pending_register_' . $token);
        RateLimiter::clear('otp_attempts_' . $token);
    }

    public function test_prevent_duplicate_qr_code_generation_on_same_production()
    {
        $this->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class]);

        $cs = CoffeeShop::first();
        $this->assertNotNull($cs);

        $petani = Petani::first();
        $lahan = Lahan::firstOrCreate(
            ['id_petani' => $petani->id_petani],
            ['koordinat_x' => 109.0, 'koordinat_y' => 0.0, 'luas_lahan' => 1, 'jumlah_tanaman' => 500]
        );

        $produksi = Produksi::create([
            'id_lahan' => $lahan->id_lahan,
            'jumlah_green_bean' => 20.0,
            'tanggal_input' => date('Y-m-d'),
            'status' => 'Diterima Coffee Shop',
        ]);

        // Buat kemasan pertama
        KemasanKopi::create([
            'id_coffee_shop' => $cs->id_coffee_shop,
            'id_produksi' => $produksi->id_produksi,
            'qr_code_url' => 'TEST-DUP-' . uniqid(),
        ]);

        // Coba generate lagi via controller
        $response = $this->actingAs($cs, 'coffee_shop')
            ->post(route('coffeeshop.generate.qr', $produksi->id_produksi), [
                'foto_sangrai' => [UploadedFile::fake()->image('sangrai.jpg')],
            ]);

        $response->assertSessionHasErrors('error');
        $this->assertTrue(str_contains(session('errors')->first('error'), 'sudah pernah dibuatkan QR Code'));

        $produksi->delete();
    }
}
