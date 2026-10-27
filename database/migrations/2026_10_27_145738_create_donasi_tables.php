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
        // 1. Rekening Penampung Donasi
        Schema::create('rekening_donasi', function (Blueprint $table) {
            $table->id('id_rekening');
            $table->string('nama_bank', 50);          // BCA, BRI, Mandiri, BNI, QRIS, dll
            $table->string('nomor_rekening', 50);      // No Rekening / No E-Wallet
            $table->string('atas_nama', 100);          // Nama Pemilik Rekening
            $table->string('keterangan', 255)->nullable(); // Catatan opsional
            $table->boolean('is_active')->default(true); // Rekening tujuan yang aktif dipilih
            $table->timestamps();
        });

        // 2. Transaksi Kas Donasi (Uang Masuk dari Donatur & Uang Keluar / Penyaluran)
        Schema::create('donasi_transaksi', function (Blueprint $table) {
            $table->id('id_transaksi');
            $table->unsignedBigInteger('id_rekening')->nullable();
            $table->enum('jenis', ['masuk', 'keluar']); // masuk = donasi masuk, keluar = penyaluran
            $table->decimal('nominal', 15, 2);
            $table->date('tanggal');
            $table->string('nama_pihak', 150);         // Nama Donatur (jika masuk) atau Nama Penerima/Kelompok Tani (jika keluar)
            $table->string('kategori', 100)->nullable(); // Misal: Bibit Kopi, Cup of Coffee, Kas Operasional Petani
            $table->text('keterangan')->nullable();
            $table->string('bukti_transfer')->nullable(); // Foto / slip bukti
            $table->timestamps();

            $table->foreign('id_rekening')->references('id_rekening')->on('rekening_donasi')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donasi_transaksi');
        Schema::dropIfExists('rekening_donasi');
    }
};
