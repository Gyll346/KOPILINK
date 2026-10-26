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
        Schema::create('panen_ceri', function (Blueprint $table) {
            $table->id('id_panen_ceri');
            $table->unsignedBigInteger('id_lahan');
            $table->float('jumlah_ceri'); // Satuan Kg buah kopi ceri basah
            $table->date('tanggal_panen');
            $table->text('catatan')->nullable(); // Kualitas/catatan sortasi (misal: petik merah)
            $table->text('foto_ceri')->nullable(); // JSON array path foto ceri
            $table->timestamps();

            $table->foreign('id_lahan')->references('id_lahan')->on('lahan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('panen_ceri');
    }
};
