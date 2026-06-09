<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    protected $table = 'notifikasis';
    protected $primaryKey = 'id_notifikasi';
    protected $fillable = ['id_user', 'judul', 'pesan', 'icon', 'warna', 'dibaca'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}