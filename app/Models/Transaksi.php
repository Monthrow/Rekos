<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model {
    protected $table = 'transaksis';
    protected $primaryKey = 'id_transaksi';
    protected $fillable = ['id_user', 'id_barang', 'jumlah_beli', 'total_harga', 'status'];

    public function user() {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function barang() {
        return $this->belongsTo(Barang::class, 'id_barang');
    }
}