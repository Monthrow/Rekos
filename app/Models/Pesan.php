<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesan extends Model {
    protected $table = 'pesans';
    protected $primaryKey = 'id_pesan';
    
    // PERBAIKAN: Menambahkan 'id_pengirim' ke dalam $fillable agar data tidak diblokir oleh Laravel
    protected $fillable = [
        'id_pembeli', 
        'id_penjual', 
        'id_barang', 
        'id_pengirim', 
        'isi_pesan'
    ];

    public function pembeli() {
        return $this->belongsTo(User::class, 'id_pembeli');
    }

    public function penjual() {
        return $this->belongsTo(User::class, 'id_penjual');
    }

    public function barang() {
        return $this->belongsTo(Barang::class, 'id_barang');
    }
}