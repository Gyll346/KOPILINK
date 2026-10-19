<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produksi extends Model
{
    use HasFactory;

    protected $table = 'produksi';
    protected $primaryKey = 'id_produksi';

    protected $fillable = [
        'id_lahan',
        'jumlah_green_bean',
        'tanggal_input',
        'status',
        'foto_green_bean',
    ];

    protected $casts = [
        'jumlah_green_bean' => 'float',
        'tanggal_input' => 'date',
    ];

    public function lahan()
    {
        return $this->belongsTo(Lahan::class, 'id_lahan', 'id_lahan');
    }

    public function kemasan()
    {
        return $this->hasMany(KemasanKopi::class, 'id_produksi', 'id_produksi');
    }
}
