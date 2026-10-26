<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PanenCeri extends Model
{
    use HasFactory;

    protected $table = 'panen_ceri';
    protected $primaryKey = 'id_panen_ceri';

    protected $fillable = [
        'id_lahan',
        'jumlah_ceri',
        'tanggal_panen',
        'catatan',
        'foto_ceri',
    ];

    protected $casts = [
        'jumlah_ceri' => 'float',
        'tanggal_panen' => 'date',
    ];

    public function getFotoListAttribute(): array
    {
        if (!$this->foto_ceri) {
            return [];
        }
        $decoded = json_decode($this->foto_ceri, true);
        if (is_array($decoded)) {
            return $decoded;
        }
        return [$this->foto_ceri];
    }

    public function getFotoPertamaAttribute(): ?string
    {
        $list = $this->foto_list;
        return count($list) > 0 ? $list[0] : null;
    }

    public function lahan()
    {
        return $this->belongsTo(Lahan::class, 'id_lahan', 'id_lahan');
    }
}
