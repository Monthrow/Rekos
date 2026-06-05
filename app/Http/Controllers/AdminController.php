<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Barang;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class AdminController extends Controller {
    public function dashboard() {
        $total_penjual = User::where('role', 'penjual')->count();
        $total_pembeli = User::where('role', 'pembeli')->count();
        $total_barang = Barang::count();
        $total_transaksi = Transaksi::count();
        $barang_terbaru = Barang::with('penjual')->orderBy('id_barang', 'desc')->limit(6)->get();

        return view('admin.dashboard', compact('total_penjual', 'total_pembeli', 'total_barang', 'total_transaksi', 'barang_terbaru'));
    }

    public function barang(Request $request) {
        $filter_user = $request->input('id_user', 0);
        $keyword = $request->input('q', '');

        $query = Barang::with('penjual');

        if ($filter_user > 0) {
            $query->where('id_user', $filter_user);
        }
        if ($keyword !== '') {
            $query->where(function($q) use ($keyword) {
                $q->where('nama_barang', 'like', "%$keyword%")
                  ->orWhere('deskripsi', 'like', "%$keyword%");
            });
        }

        $barang = $query->orderBy('id_barang', 'desc')->get();
        return view('admin.barang', compact('barang', 'keyword'));
    }

    public function penjual() {
        $penjual = User::where('role', 'penjual')
            ->withCount('barangs')
            ->get();
            
        return view('admin.penjual', compact('penjual'));
    }

    public function hapusPenjual(Request $request) {
        $user = User::where('id_user', $request->id_user)->where('role', 'penjual')->firstOrFail();
        $user->delete();

        return back()->with('success', 'Penjual berhasil dihapus beserta seluruh datanya.');
    }
}