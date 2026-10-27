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
        'foto_sangrai',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getFotoSangraiListAttribute(): array
    {
        if (!$this->foto_sangrai) {
            return [];
        }
        $decoded = json_decode($this->foto_sangrai, true);
        if (is_array($decoded)) {
            return $decoded;
        }
        return [$this->foto_sangrai];
    }

    public function getFotoSangraiPertamaAttribute(): ?string
    {
        $list = $this->foto_sangrai_list;
        return count($list) > 0 ? $list[0] : null;
    }

    public function coffeeShop()
    {
        return $this->belongsTo(CoffeeShop::class, 'id_coffee_shop', 'id_coffee_shop');
    }

    public function produksi()
    {
        return $this->belongsTo(Produksi::class, 'id_produksi', 'id_produksi');
    }
}
