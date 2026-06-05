<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Barang;
use App\Models\Transaksi;

class ProfileController extends Controller
{
    // 1. Menampilkan halaman ringkasan profil + Riwayat Transaksi dinamis
    public function index()
    {
        $user = Auth::user();
        $riwayat_pembelian = collect();
        $riwayat_penjualan = collect();

        // Jika user adalah Pembeli, ambil riwayat transaksi yang pernah dia lakukan
        if (strtolower($user->role) === 'pembeli') {
            $riwayat_pembelian = Transaksi::with('barang')
                ->where('id_user', $user->id_user) // Berdasarkan ID pembeli di tabel transaksi
                ->orderBy('id_transaksi', 'desc')
                ->get();
        } 
        // Jika user adalah Penjual, ambil data transaksi masuk yang membeli barang miliknya
        elseif (strtolower($user->role) === 'penjual') {
            // Ambil semua daftar ID barang yang dimiliki oleh penjual ini
            $id_barang_saya = Barang::where('id_user', $user->id_user)->pluck('id_barang');

            // Tarik seluruh transaksi yang melibatkan barang-barang milik penjual tersebut
            $riwayat_penjualan = Transaksi::with(['barang', 'user']) // 'user' di sini merujuk ke relasi pembeli
                ->whereIn('id_barang', $id_barang_saya)
                ->orderBy('id_transaksi', 'desc')
                ->get();
        }

        return view('profile.index', compact('user', 'riwayat_pembelian', 'riwayat_penjualan'));
    }

    // 2. Fitur Switch Role (Tukar Peran antara Pembeli & Penjual)
    public function switchRole(Request $request)
    {
        $user = User::findOrFail(Auth::id());

        // Validasi: Jika user masih 'pembeli' dan mau beralih ke 'penjual' tapi belum melengkapi data toko
        if (strtolower($user->role) === 'pembeli' && (empty($user->alamat) || empty($user->no_telp))) {
            return redirect()->route('barang.jual')->with('error', 'Silakan lengkapi data toko Anda terlebih dahulu untuk menjadi penjual.');
        }

        // Proses tukar role
        if (strtolower($user->role) === 'pembeli') {
            $user->role = 'penjual';
            $pesan = 'Berhasil masuk ke Mode Penjual.';
        } else {
            $user->role = 'pembeli';
            $pesan = 'Berhasil masuk ke Mode Pembeli.';
        }

        $user->save();

        return back()->with('success', $pesan);
    }

    // 3. Menampilkan formulir edit profil
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    // 4. Memproses update data profil & password ke database
    public function update(Request $request)
    {
        $user = User::findOrFail(Auth::id());

        $request->validate([
            'username' => 'required|string|max:255',
            'no_telp' => 'nullable|string|max:15',
            'alamat' => 'nullable|string',
            'password' => 'nullable|string|min:8|confirmed', // field konfirmasi harus bernama password_confirmation
        ]);

        $user->username = $request->username;
        $user->no_telp = $request->no_telp;
        $user->alamat = $request->alamat;

        // Jika user mengisi password baru, lakukan hash bcrypt
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        return redirect()->route('profile.index')->with('success', 'Profil Anda berhasil diperbarui!');
    }
}