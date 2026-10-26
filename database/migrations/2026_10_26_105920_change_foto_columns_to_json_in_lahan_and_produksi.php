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
        Schema::table('lahan', function (Blueprint $table) {
            $table->text('foto_lahan')->nullable()->change();
        });

        Schema::table('produksi', function (Blueprint $table) {
            $table->text('foto_green_bean')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lahan', function (Blueprint $table) {
            $table->string('foto_lahan', 255)->nullable()->change();
        });

        Schema::table('produksi', function (Blueprint $table) {
            $table->string('foto_green_bean', 255)->nullable()->change();
        });
    }
};
