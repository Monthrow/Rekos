<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model {
    protected $table = 'barangs';
    protected $primaryKey = 'id_barang';
    protected $fillable = ['nama_barang', 'deskripsi', 'harga', 'jumlah', 'gambar', 'id_user', 'id_pembeli', 'waktu_beli', 'status_barang'];

    public function penjual() {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function pembeli() {
        return $this->belongsTo(User::class, 'id_pembeli');
    }

    public function transaksis() {
        return $this->hasMany(Transaksi::class, 'id_barang');
    }

    public function pesans() {
        return $this->hasMany(Pesan::class, 'id_barang');
    }
}