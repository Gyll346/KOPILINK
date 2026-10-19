<?php

namespace Tests\Feature;

use App\Models\CoffeeShop;
use App\Models\KemasanKopi;
use App\Models\Lahan;
use App\Models\Petani;
use App\Models\Produksi;
use App\Services\WhatsAppOtpService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KopiLinkTest extends TestCase
{
    public function test_business_rules_and_traceability_lifecycle()
    {
        // 1. Test Phone Normalization
        $service = new WhatsAppOtpService();
        $this->assertEquals(6281234567890, $service->formatPhoneNumber('081234567890'));
        $this->assertEquals(6281234567890, $service->formatPhoneNumber('+6281234567890'));

        // 2. Test Auto Coffee Variety assignment
        $petaniPasak = Petani::firstOrCreate(
            ['username' => 'test_pasak'],
            [
                'password' => bcrypt('password'),
                'nama' => 'Test Pasak',
                'kontak' => 62899999901,
                'alamat' => 'Desa Pasak Piang',
                'kelompok_tani' => 'Pasak Piang',
                'is_verified' => true,
            ]
        );

        $lahan1 = Lahan::create([
            'id_petani' => $petaniPasak->id_petani,
            'koordinat_x' => 109.1,
            'koordinat_y' => -0.1,
            'luas_lahan' => 1.5,
            'jumlah_tanaman' => 1000,
        ]);
        $this->assertEquals('Robusta', $lahan1->jenis_kopi);

        $petaniPadi = Petani::firstOrCreate(
            ['username' => 'test_padi'],
            [
                'password' => bcrypt('password'),
                'nama' => 'Test Padi',
                'kontak' => 62899999902,
                'alamat' => 'Desa Padi Jaya',
                'kelompok_tani' => 'Padi Jaya',
                'is_verified' => true,
            ]
        );

        $lahan2 = Lahan::create([
            'id_petani' => $petaniPadi->id_petani,
            'koordinat_x' => 109.2,
            'koordinat_y' => 0.1,
            'luas_lahan' => 2.0,
            'jumlah_tanaman' => 1500,
        ]);
        $this->assertEquals('Liberika', $lahan2->jenis_kopi);

        // 3. Test Production Lifecycle (Tersedia -> Diterima Coffee Shop)
        $produksi = Produksi::create([
            'id_lahan' => $lahan1->id_lahan,
            'jumlah_green_bean' => 50.0,
            'tanggal_input' => date('Y-m-d'),
            'status' => 'Tersedia',
        ]);
        $this->assertEquals('Tersedia', $produksi->status);

        $cs = CoffeeShop::first();
        $this->assertNotNull($cs);

        $produksi->update(['status' => 'Diterima Coffee Shop']);
        $this->assertEquals('Diterima Coffee Shop', $produksi->fresh()->status);

        // 4. Test QR Packaging creation and Public Page
        $kemasan = KemasanKopi::create([
            'id_coffee_shop' => $cs->id_coffee_shop,
            'id_produksi' => $produksi->id_produksi,
            'qr_code_url' => 'TEST-ROB-UNIT01',
        ]);

        $response = $this->get('/trace/' . $kemasan->qr_code_url);
        $response->assertStatus(200);
        $response->assertSee('Robusta');
        $response->assertSee('Test Pasak');
    }
}
