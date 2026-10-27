<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekeningDonasi extends Model
{
    use HasFactory;

    protected $table = 'rekening_donasi';
    protected $primaryKey = 'id_rekening';

    protected $fillable = [
        'nama_bank',
        'nomor_rekening',
        'atas_nama',
        'keterangan',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function transaksi()
    {
        return $this->hasMany(DonasiTransaksi::class, 'id_rekening', 'id_rekening');
    }
}
