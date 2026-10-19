<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Tabel Petani
        Schema::create('petani', function (Blueprint $table) {
            $table->id('id_petani');
            $table->string('username', 50)->unique();
            $table->string('password', 255);
            $table->string('nama', 100);
            $table->unsignedBigInteger('kontak')->unique();
            $table->text('alamat');
            $table->enum('kelompok_tani', ['Pasak Piang', 'Padi Jaya']);
            $table->string('otp_code', 6)->nullable();
            $table->dateTime('otp_expired_at')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->timestamps();
        });

        // 2. Tabel Lahan
        Schema::create('lahan', function (Blueprint $table) {
            $table->id('id_lahan');
            $table->unsignedBigInteger('id_petani');
            $table->decimal('koordinat_x', 11, 8); // Longitude
            $table->decimal('koordinat_y', 10, 8); // Latitude
            $table->float('luas_lahan'); // Satuan Hektar
            $table->unsignedInteger('jumlah_tanaman'); // Populasi pohon
            $table->enum('jenis_kopi', ['Robusta', 'Liberika']);
            $table->string('foto_lahan', 255)->nullable();
            $table->timestamps();

            $table->foreign('id_petani')->references('id_petani')->on('petani')->onDelete('cascade');
        });

        // 3. Tabel Produksi
        Schema::create('produksi', function (Blueprint $table) {
            $table->id('id_produksi');
            $table->unsignedBigInteger('id_lahan');
            $table->float('jumlah_green_bean'); // Satuan Kg
            $table->date('tanggal_input');
            $table->enum('status', ['Tersedia', 'Diterima Coffee Shop'])->default('Tersedia');
            $table->string('foto_green_bean', 255)->nullable();
            $table->timestamps();

            $table->foreign('id_lahan')->references('id_lahan')->on('lahan')->onDelete('cascade');
        });

        // 4. Tabel Coffee Shop
        Schema::create('coffee_shop', function (Blueprint $table) {
            $table->id('id_coffee_shop');
            $table->string('username', 50)->unique();
            $table->string('password', 255);
            $table->string('nama_toko', 100);
            $table->timestamps();
        });

        // 5. Tabel Kemasan Kopi
        Schema::create('kemasan_kopi', function (Blueprint $table) {
            $table->id('id_kemasan');
            $table->unsignedBigInteger('id_coffee_shop');
            $table->unsignedBigInteger('id_produksi');
            $table->string('qr_code_url', 255)->unique(); // Slug atau URL unik kemasan
            $table->timestamps();

            $table->foreign('id_coffee_shop')->references('id_coffee_shop')->on('coffee_shop')->onDelete('cascade');
            $table->foreign('id_produksi')->references('id_produksi')->on('produksi')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kemasan_kopi');
        Schema::dropIfExists('coffee_shop');
        Schema::dropIfExists('produksi');
        Schema::dropIfExists('lahan');
        Schema::dropIfExists('petani');
    }
};
