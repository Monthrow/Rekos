<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable {
    use Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id_user';
    protected $fillable = ['username', 'email', 'password', 'role', 'no_telp', 'alamat'];
    protected $hidden = ['password', 'remember_token'];

    public function barangs() {
        return $this->hasMany(Barang::class, 'id_user');
    }

    public function transaksis() {
        return $this->hasMany(Transaksi::class, 'id_user');
    }
}