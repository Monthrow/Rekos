<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Transaksi;
use App\Models\Notifikasi;
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
            // 1. Simpan transaksi
            Transaksi::create([
                'id_barang'   => $barang->id_barang,
                'id_user'     => Auth::id(),
                'jumlah_beli' => $request->jumlah_beli,
                'total_harga' => $request->total_harga,
                'status'      => 'Pending'
            ]);

            // 2. Update status barang
            $barang->update([
                'id_pembeli'    => Auth::id(),
                'waktu_beli'    => now(),
                'status_barang' => 'pending'
            ]);

            // 3. Notifikasi ke PEMBELI
            NotifikasiController::kirim(
                Auth::id(),
                'Pembayaran Berhasil!',
                'Kamu berhasil memesan "' . $barang->nama_barang . '" seharga Rp ' . number_format($request->total_harga, 0, ',', '.') . '. Menunggu konfirmasi penjual.',
                'fas fa-check-circle',
                'green'
            );

            // 4. Notifikasi ke PENJUAL
            NotifikasiController::kirim(
                $barang->id_user,
                'Ada Pesanan Masuk!',
                Auth::user()->username . ' memesan "' . $barang->nama_barang . '" seharga Rp ' . number_format($request->total_harga, 0, ',', '.') . '. Segera konfirmasi transaksi.',
                'fas fa-shopping-bag',
                'amber'
            );
        });

        return redirect()->route('dashboard')->with('success', 'Pembayaran berhasil! Barang berhasil di-booking.');
    }
}