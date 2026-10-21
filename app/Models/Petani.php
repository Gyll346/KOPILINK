<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Petani extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'petani';
    protected $primaryKey = 'id_petani';

    protected $fillable = [
        'username',
        'password',
        'nama',
        'kontak',
        'alamat',
        'kelompok_tani',
        'foto_profil',
        'otp_code',
        'otp_expired_at',
        'is_verified',
    ];

    protected $hidden = [
        'password',
        'otp_code',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'otp_expired_at' => 'datetime',
        'kontak' => 'integer',
    ];

    public function lahan()
    {
        return $this->hasMany(Lahan::class, 'id_petani', 'id_petani');
    }
}
