<?php

namespace Database\Seeders;

use App\Models\CoffeeShop;
use App\Models\KemasanKopi;
use App\Models\Lahan;
use App\Models\Petani;
use App\Models\Produksi;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Coffee Shop Mitra
        $cs1 = CoffeeShop::firstOrCreate(
            ['username' => 'roastery_senja'],
            [
                'password' => Hash::make('KopiLink2026!'),
                'nama_toko' => '101 Coffe House',
            ]
        );

        $cs2 = CoffeeShop::firstOrCreate(
            ['username' => 'biji_bumi_coffee'],
            [
                'password' => Hash::make('BumiHijau2026!'),
                'nama_toko' => 'Biji Bumi Specialty Coffee',
            ]
        );

        // 2. Seed Petani Terverifikasi
        $petani1 = Petani::firstOrCreate(
            ['username' => 'petani_pasak'],
            [
                'password' => Hash::make('password123'),
                'nama' => 'Pak Ahmad Subarjo',
                'kontak' => 6281234567890,
                'alamat' => 'Desa Pasak Piang, Kec. Sungai Ambawang',
                'kelompok_tani' => 'Pasak Piang',
                'is_verified' => true,
            ]
        );

        $petani2 = Petani::firstOrCreate(
            ['username' => 'petani_padi'],
            [
                'password' => Hash::make('password123'),
                'nama' => 'Pak Bambang Hartono',
                'kontak' => 6281234567891,
                'alamat' => 'Desa Padi Jaya, Kec. Kuala Mandor B',
                'kelompok_tani' => 'Padi Jaya',
                'is_verified' => true,
            ]
        );

        // 3. Seed Lahan (Auto: Pasak Piang -> Robusta, Padi Jaya -> Liberika)
        $lahan1 = Lahan::firstOrCreate(
            ['id_petani' => $petani1->id_petani],
            [
                'koordinat_x' => 109.34250000,
                'koordinat_y' => -0.02630000,
                'luas_lahan' => 1.75,
                'jumlah_tanaman' => 1400,
                'jenis_kopi' => 'Robusta',
                'foto_lahan' => null,
            ]
        );

        $lahan2 = Lahan::firstOrCreate(
            ['id_petani' => $petani2->id_petani],
            [
                'koordinat_x' => 109.41280000,
                'koordinat_y' => 0.05140000,
                'luas_lahan' => 2.50,
                'jumlah_tanaman' => 2100,
                'jenis_kopi' => 'Liberika',
                'foto_lahan' => null,
            ]
        );

        // 4. Seed Produksi
        $prod1 = Produksi::firstOrCreate(
            ['id_lahan' => $lahan1->id_lahan, 'tanggal_input' => '2026-10-01'],
            [
                'jumlah_green_bean' => 120.0,
                'status' => 'Diterima Coffee Shop',
                'foto_green_bean' => null,
            ]
        );

        $prod2 = Produksi::firstOrCreate(
            ['id_lahan' => $lahan2->id_lahan, 'tanggal_input' => '2026-10-05'],
            [
                'jumlah_green_bean' => 85.0,
                'status' => 'Tersedia',
                'foto_green_bean' => null,
            ]
        );

        // 5. Seed Kemasan Kopi Ber-QR
        KemasanKopi::firstOrCreate(
            ['qr_code_url' => 'KPL-ROB-2026-BBF01'],
            [
                'id_coffee_shop' => $cs1->id_coffee_shop,
                'id_produksi' => $prod1->id_produksi,
            ]
        );
    }
}
