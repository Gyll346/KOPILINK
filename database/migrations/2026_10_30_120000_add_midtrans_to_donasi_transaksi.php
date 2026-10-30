<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('donasi_transaksi', function (Blueprint $table) {
            $table->string('order_id', 100)->nullable()->unique()->after('id_rekening');
            $table->string('status', 20)->default('settlement')->after('bukti_transfer');
            // status: pending, settlement, expire, cancel, deny
        });
    }

    public function down(): void
    {
        Schema::table('donasi_transaksi', function (Blueprint $table) {
            $table->dropColumn(['order_id', 'status']);
        });
    }
};

