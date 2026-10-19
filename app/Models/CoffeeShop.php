<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class CoffeeShop extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'coffee_shop';
    protected $primaryKey = 'id_coffee_shop';

    protected $fillable = [
        'username',
        'password',
        'nama_toko',
    ];

    protected $hidden = [
        'password',
    ];

    public function kemasan()
    {
        return $this->hasMany(KemasanKopi::class, 'id_coffee_shop', 'id_coffee_shop');
    }
}
