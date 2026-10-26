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
        Schema::table('kontak_pesan', function (Blueprint $table) {
            if (!Schema::hasColumn('kontak_pesan', 'id_pesan') && Schema::hasColumn('kontak_pesan', 'id')) {
                $table->renameColumn('id', 'id_pesan');
            }
            if (!Schema::hasColumn('kontak_pesan', 'nama')) {
                $table->string('nama', 100);
            }
            if (!Schema::hasColumn('kontak_pesan', 'email')) {
                $table->string('email', 100);
            }
            if (!Schema::hasColumn('kontak_pesan', 'subjek')) {
                $table->string('subjek', 150);
            }
            if (!Schema::hasColumn('kontak_pesan', 'pesan')) {
                $table->text('pesan');
            }
            if (!Schema::hasColumn('kontak_pesan', 'is_read')) {
                $table->boolean('is_read')->default(false);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kontak_pesan', function (Blueprint $table) {
            $table->dropColumn(['nama', 'email', 'subjek', 'pesan', 'is_read']);
        });
    }
};
