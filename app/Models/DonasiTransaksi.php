<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonasiTransaksi extends Model
{
    use HasFactory;

    protected $table = 'donasi_transaksi';
    protected $primaryKey = 'id_transaksi';

    protected $fillable = [
        'id_rekening',
        'order_id',
        'jenis',
        'nominal',
        'tanggal',
        'nama_pihak',
        'kategori',
        'keterangan',
        'bukti_transfer',
        'status',
    ];

    protected $casts = [
        'nominal' => 'float',
        'tanggal' => 'date',
    ];

    public function rekening()
    {
        return $this->belongsTo(RekeningDonasi::class, 'id_rekening', 'id_rekening');
    }
}
