<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KemasanKopi extends Model
{
    use HasFactory;

    protected $table = 'kemasan_kopi';
    protected $primaryKey = 'id_kemasan';

    protected $fillable = [
        'id_coffee_shop',
        'id_produksi',
        'qr_code_url',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function coffeeShop()
    {
        return $this->belongsTo(CoffeeShop::class, 'id_coffee_shop', 'id_coffee_shop');
    }

    public function produksi()
    {
        return $this->belongsTo(Produksi::class, 'id_produksi', 'id_produksi');
    }
}
