<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    public function checkout($id) {
        $barang = Barang::with('penjual')->findOrFail($id);
        if ($barang->id_user == Auth::id()) {
            return redirect()->route('dashboard')->with('error', 'Tidak bisa membeli barang sendiri!');
        }
        return view('transaksi.checkout', compact('barang'));
    }

    public function prosesBeli(Request $request, $id) {
        $barang = Barang::findOrFail($id);
        $request->validate(['jumlah_beli' => 'required|integer|min:1|max:'.$barang->jumlah]);

        $total = $barang->harga * $request->jumlah_beli;

        return view('transaksi.bayar', compact('barang', 'total', 'request'));
    }

    public function konfirmasiBayar(Request $request, $id) {
        $barang = Barang::findOrFail($id);
        
        DB::transaction(function () use ($barang, $request) {
            // 1. Simpan ke tbl_transaksi
            Transaksi::create([
                'id_barang' => $barang->id_barang,
                'id_pembeli' => Auth::id(),
                'jumlah_beli' => $request->jumlah_beli,
                'total_harga' => $request->total_harga,
                'status' => 'Pending'
            ]);

            // 2. Kunci / booking barang & catat waktu bayar
            $barang->update([
                'id_pembeli' => Auth::id(),
                'waktu_beli' => now(),
                'status_barang' => 'dibooking'
            ]);
        });

        return redirect()->route('dashboard')->with('success', 'Pembayaran QRIS diproses! Barang berhasil di-booking.');
    }
}