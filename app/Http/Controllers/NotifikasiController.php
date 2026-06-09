<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    // Tandai semua notifikasi sebagai sudah dibaca
    public function bacaSemua()
    {
        Notifikasi::where('id_user', Auth::id())
            ->where('dibaca', false)
            ->update(['dibaca' => true]);

        return back();
    }

    // Tandai satu notifikasi sebagai dibaca
    public function baca($id)
    {
        Notifikasi::where('id_notifikasi', $id)
            ->where('id_user', Auth::id())
            ->update(['dibaca' => true]);

        return back();
    }

    // Helper static untuk membuat notifikasi (dipanggil dari controller lain)
    public static function kirim($id_user, $judul, $pesan, $icon = 'fas fa-bell', $warna = 'blue')
    {
        Notifikasi::create([
            'id_user' => $id_user,
            'judul'   => $judul,
            'pesan'   => $pesan,
            'icon'    => $icon,
            'warna'   => $warna,
        ]);
    }
}