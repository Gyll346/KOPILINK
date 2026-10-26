<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KontakPesan extends Model
{
    use HasFactory;

    protected $table = 'kontak_pesan';
    protected $primaryKey = 'id_pesan';

    protected $fillable = [
        'nama',
        'email',
        'subjek',
        'pesan',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];
}
