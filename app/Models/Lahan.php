<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lahan extends Model
{
    use HasFactory;

    protected $table = 'lahan';
    protected $primaryKey = 'id_lahan';

    protected $fillable = [
        'id_petani',
        'koordinat_x',
        'koordinat_y',
        'luas_lahan',
        'jumlah_tanaman',
        'jenis_kopi',
        'foto_lahan',
    ];

    protected $casts = [
        'koordinat_x' => 'float',
        'koordinat_y' => 'float',
        'luas_lahan' => 'float',
        'jumlah_tanaman' => 'integer',
    ];

    protected static function booted(): void
    {
        static::creating(function (Lahan $lahan) {
            if (!$lahan->jenis_kopi && $lahan->id_petani) {
                $petani = Petani::find($lahan->id_petani);
                if ($petani) {
                    if ($petani->kelompok_tani === 'Pasak Piang') {
                        $lahan->jenis_kopi = 'Robusta';
                    } elseif ($petani->kelompok_tani === 'Padi Jaya') {
                        $lahan->jenis_kopi = 'Liberika';
                    }
                }
            }
        });
    }

    public function getFotoListAttribute(): array
    {
        if (!$this->foto_lahan) {
            return [];
        }
        $decoded = json_decode($this->foto_lahan, true);
        if (is_array($decoded)) {
            return $decoded;
        }
        return [$this->foto_lahan];
    }

    public function getFotoPertamaAttribute(): ?string
    {
        $list = $this->foto_list;
        return count($list) > 0 ? $list[0] : null;
    }

    public function petani()
    {
        return $this->belongsTo(Petani::class, 'id_petani', 'id_petani');
    }

    public function produksi()
    {
        return $this->hasMany(Produksi::class, 'id_lahan', 'id_lahan');
    }

    public function panenCeri()
    {
        return $this->hasMany(PanenCeri::class, 'id_lahan', 'id_lahan');
    }
}
