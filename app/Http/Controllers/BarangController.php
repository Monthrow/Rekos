<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\User;
use App\Models\Pesan;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BarangController extends Controller {
    
    public function detail(Request $request, $id) {
        $barang = Barang::with('penjual')->findOrFail($id);
        
        $id_user_login = (int) Auth::id();
        $id_penjual = (int) $barang->id_user;

        // 1. OTOMATISASI RESET BOOKING (Jika status pending & lewat dari 10 menit / 600 detik)
        if ($barang->status_barang === 'pending' && $barang->waktu_beli) {
            $waktu_habis_timestamp = strtotime($barang->waktu_beli) + 600;
            if (time() > $waktu_habis_timestamp) {
                DB::transaction(function () use ($barang) {
                    $barang->status_barang = 'tersedia';
                    $barang->id_pembeli = null;
                    $barang->waktu_beli = null;
                    $barang->save();

                    Transaksi::where('id_barang', $barang->id_barang)
                        ->where('status', 'Pending')
                        ->update(['status' => 'Batal']);
                });
                return redirect()->route('barang.detail', $id)->with('error', 'Waktu transaksi habis. Booking otomatis dibatalkan.');
            }
        }

        // 2. PENENTUAN TARGET CHAT (Siapa lawan bicara?)
        $chat_buyer_id = (int)$request->query('buyer_id', 0);
        $target_pembeli = 0;

        if ($id_user_login !== $id_penjual) {
            $target_pembeli = $id_user_login;
        } else {
            if ($chat_buyer_id > 0) {
                $target_pembeli = $chat_buyer_id;
            } elseif ($barang->id_pembeli) {
                $target_pembeli = (int)$barang->id_pembeli;
            }
        }

        // 3. AMBIL DAFTAR INBOX (Hanya untuk penjual)
        $daftar_inbox = collect();
        if ($id_user_login === $id_penjual) {
            $daftar_inbox = Pesan::join('users', 'pesans.id_pembeli', '=', 'users.id_user')
                ->select('pesans.id_pembeli', 'users.username')
                ->where('pesans.id_barang', $id)
                ->where('pesans.id_pembeli', '!=', $id_penjual)
                ->distinct()
                ->get();
        }

        // 4. PROTEKSI STRID & VALIDASI HAK AKSES CHAT
        $show_chat = true;
        
        // Kondisi A: Jika barang sudah terjual, chat ditutup untuk siapa saja
        if ($barang->status_barang === 'terjual') {
            $show_chat = false; 
        }
        
        // Kondisi B: Jika penjual belum memilih pembeli dari daftar inboxnya
        if ($id_user_login === $id_penjual && $target_pembeli === 0) {
            $show_chat = false; 
        }

        // Kondisi C: JIKA BARANG SEDANG DI-BOOKING (Pending)
        if ($barang->status_barang === 'pending') {
            // Yang boleh akses CHAT HANYA penjual ATAU pembeli yang sah membooking (Naya)
            if ($id_user_login !== $id_penjual && $id_user_login !== (int)$barang->id_pembeli) {
                $show_chat = false; // Rinda otomatis tidak diberi akses kotak chat
            }
        }

        // 5. AMBIL RIWAYAT CHAT YANG SPESIFIK
        $pesans = collect();
        if ($show_chat && $target_pembeli > 0) {
            $pesans = Pesan::where('id_barang', $id)
                ->where('id_pembeli', $target_pembeli)
                ->where('id_penjual', $id_penjual)
                ->orderBy('id_pesan', 'asc')
                ->get();
        }

        // 6. PENENTUAN STATUS BOOKING & COUNTDOWN
        $is_buying = ($barang->id_pembeli == $id_user_login && $barang->status_barang === 'pending');
        $waktu_habis = 0;
        if ($barang->waktu_beli) {
            $waktu_habis = strtotime($barang->waktu_beli) + 600;
        }

        return view('barang.detail', compact(
            'barang', 'pesans', 'is_buying', 'waktu_habis', 
            'id_user_login', 'id_penjual', 'target_pembeli', 
            'daftar_inbox', 'show_chat', 'chat_buyer_id'
        ));
    }

    public function kirimPesan(Request $request, $id) {
        $request->validate(['isi_pesan' => 'required|string']);
        $barang = Barang::findOrFail($id);
        
        $id_user_login = (int) Auth::id();
        $id_penjual = (int) $barang->id_user;

        // Validasi Keamanan: Jika barang di-booking, cegah pihak ketiga (Rinda) menembak route
        if ($barang->status_barang === 'pending' && $id_user_login !== $id_penjual && $id_user_login !== (int)$barang->id_pembeli) {
            return back()->with('error', 'Anda tidak memiliki akses ke obrolan ini.');
        }

        if ($barang->status_barang === 'terjual') {
            return back()->with('error', 'Barang sudah terjual, obrolan ditutup.');
        }

        $chat_buyer_id = (int)$request->query('buyer_id', 0);
        $target_pembeli = 0;

        if ($id_user_login !== $id_penjual) {
            $target_pembeli = $id_user_login;
        } else {
            if ($chat_buyer_id > 0) {
                $target_pembeli = $chat_buyer_id;
            } elseif ($barang->id_pembeli) {
                $target_pembeli = (int)$barang->id_pembeli;
            }
        }

        if ($target_pembeli === 0) {
            return back()->with('error', 'Gagal mengirim pesan. Ruang obrolan tidak valid.');
        }

        Pesan::create([
            'id_pembeli'  => $target_pembeli,
            'id_penjual'  => $id_penjual,
            'id_barang'   => $id,
            'id_pengirim' => $id_user_login,
            'isi_pesan'   => trim($request->isi_pesan)
        ]);

        return back();
    }

    public function halamanJual() {
        $user = Auth::user();
        return view('barang.jual', compact('user'));
    }

    public function daftarPenjual(Request $request) {
        $request->validate([
            'alamat' => 'required|string',
            'no_telp' => 'required|string',
        ]);

        $user = User::findOrFail(Auth::id());
        $user->alamat = $request->alamat;
        $user->no_telp = $request->no_telp;
        $user->role = 'penjual';
        $user->save();

        return redirect()->route('barang.jual')->with('success', 'Selamat! Akun Anda telah di-upgrade menjadi Penjual.');
    }

    public function storeBarang(Request $request) {
        $request->validate([
            'nama_barang' => 'required|string|max:150',
            'harga' => 'required|numeric',
            'jumlah' => 'required|numeric',
            'deskripsi' => 'required|string',
            'gambar' => 'required|image|mimes:jpeg,jpg,png,webp|max:2048',
        ]);

        $fileName = time() . '_' . $request->file('gambar')->getClientOriginalName();
        $request->file('gambar')->move(public_path('assets/img'), $fileName);

        Barang::create([
            'nama_barang' => $request->nama_barang,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
            'jumlah' => $request->jumlah,
            'gambar' => $fileName,
            'id_user' => Auth::id(),
            'status_barang' => 'tersedia'
        ]);

        return redirect()->route('barang.saya')->with('success', 'Barang baru berhasil ditambahkan!');
    }

    public function barangSaya() {
        $barangs = Barang::where('id_user', Auth::id())->orderBy('id_barang', 'desc')->get();
        return view('barang.barang_saya', compact('barangs'));
    }

    public function editBarang($id) {
        $barang = Barang::where('id_barang', $id)->where('id_user', Auth::id())->firstOrFail();
        return view('barang.edit_barang', compact('barang'));
    }

    public function updateBarang(Request $request, $id) {
        $barang = Barang::where('id_barang', $id)->where('id_user', Auth::id())->firstOrFail();
        
        $request->validate([
            'nama_barang' => 'required|string|max:150',
            'harga' => 'required|numeric',
            'jumlah' => 'required|numeric',
            'deskripsi' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
        ]);

        $barang->nama_barang = $request->nama_barang;
        $barang->harga = $request->harga;
        $barang->jumlah = $request->jumlah;
        $barang->deskripsi = $request->deskripsi;

        if ($request->hasFile('gambar')) {
            $fileName = time() . '_' . $request->file('gambar')->getClientOriginalName();
            $request->file('gambar')->move(public_path('assets/img'), $fileName);
            $barang->gambar = $fileName;
        }

        $barang->save();
        return redirect()->route('barang.saya')->with('success', 'Barang berhasil diperbarui!');
    }

    public function hapusBarang(Request $request) {
        $barang = Barang::where('id_barang', $request->hapus_id)->where('id_user', Auth::id())->firstOrFail();
        $barang->delete();
        return redirect()->route('barang.saya')->with('success', 'Barang berhasil dihapus.');
    }

    public function halamanBayar($id) {
        $barang = Barang::with('penjual')->findOrFail($id);
        if ($barang->id_user == Auth::id()) {
            return redirect()->route('dashboard');
        }
        return view('barang.bayar', compact('barang'));
    }

    public function prosesBayar(Request $request, $id) {
        $barang = Barang::findOrFail($id);

        if ($barang->status_barang !== 'tersedia') {
            return back()->with('error', 'Barang sudah tidak tersedia untuk dibeli.');
        }

        DB::transaction(function () use ($barang) {
            $barang->id_pembeli = Auth::id();
            $barang->waktu_beli = now();
            $barang->status_barang = 'pending';
            $barang->save();

            Transaksi::create([
                'id_user' => Auth::id(),
                'id_barang' => $barang->id_barang,
                'jumlah_beli' => 1,
                'total_harga' => $barang->harga,
                'status' => 'Pending'
            ]);
        });

        return redirect()->route('barang.detail', $id)->with('success', 'Pembayaran Berhasil Diproses! Silakan hubungi penjual melalui chat.');
    }

    public function prosesKonfirmasi($id) {
        $barang = Barang::where('id_barang', $id)->where('id_user', Auth::id())->firstOrFail();

        DB::transaction(function () use ($barang) {
            $barang->status_barang = 'terjual';
            $barang->save();

            Transaksi::where('id_barang', $barang->id_barang)
                ->where('status', 'Pending')
                ->orderBy('id_transaksi', 'desc')
                ->limit(1)
                ->update(['status' => 'Selesai']);
        });

        return redirect()->route('barang.saya')->with('success', 'Transaksi berhasil dikonfirmasi selesai.');
    }

    // =========================================================================
    // FUNGSI BARU: HALAMAN PROFILE DENGAN LAPORAN STATISTIK & GRAFIK (CHART)
    // =========================================================================
    public function profil() {
        $id_user_login = Auth::id();
        $user = Auth::user();

        // 1. DATA RIWAYAT TRANSAKSI
        // Riwayat Penjualan (Pesanan Masuk dari sisi barang milik penjual)
        $riwayat_penjualan = Transaksi::with(['barang', 'user'])
            ->whereHas('barang', function($query) use ($id_user_login) {
                $query->where('id_user', $id_user_login);
            })->orderBy('id_transaksi', 'desc')->get();

        // Riwayat Pembelian (Transaksi barang yang dibeli oleh user login)
        $riwayat_pembelian = Transaksi::with(['barang'])
            ->where('id_user', $id_user_login)
            ->orderBy('id_transaksi', 'desc')->get();

        // 2. STATISTIK LAPORAN PENJUALAN (Khusus Role Penjual)
        // Rentang waktu 1 Bulan Terakhir (30 Hari)
        $sales_1_bulan_barang = Transaksi::whereHas('barang', function($q) use ($id_user_login) {
                $q->where('id_user', $id_user_login);
            })->where('status', 'Selesai')
              ->where('created_at', '>=', now()->subDays(30))->sum('jumlah_beli');

        $sales_1_bulan_dana = Transaksi::whereHas('barang', function($q) use ($id_user_login) {
                $q->where('id_user', $id_user_login);
            })->where('status', 'Selesai')
              ->where('created_at', '>=', now()->subDays(30))->sum('total_harga');

        // Rentang waktu 1 Tahun Terakhir (365 Hari)
        $sales_1_tahun_barang = Transaksi::whereHas('barang', function($q) use ($id_user_login) {
                $q->where('id_user', $id_user_login);
            })->where('status', 'Selesai')
              ->where('created_at', '>=', now()->subYear())->sum('jumlah_beli');

        $sales_1_tahun_dana = Transaksi::whereHas('barang', function($q) use ($id_user_login) {
                $q->where('id_user', $id_user_login);
            })->where('status', 'Selesai')
              ->where('created_at', '>=', now()->subYear())->sum('total_harga');

        // 3. STATISTIK LAPORAN PEMBELIAN (Untuk Seluruh User / Pembeli)
        // Rentang waktu 1 Bulan Terakhir (30 Hari)
        $buy_1_bulan_barang = Transaksi::where('id_user', $id_user_login)
            ->where('status', 'Selesai')
            ->where('created_at', '>=', now()->subDays(30))->sum('jumlah_beli');

        $buy_1_bulan_dana = Transaksi::where('id_user', $id_user_login)
            ->where('status', 'Selesai')
            ->where('created_at', '>=', now()->subDays(30))->sum('total_harga');

        // Rentang waktu 1 Tahun Terakhir (365 Hari)
        $buy_1_tahun_barang = Transaksi::where('id_user', $id_user_login)
            ->where('status', 'Selesai')
            ->where('created_at', '>=', now()->subYear())->sum('jumlah_beli');

        $buy_1_tahun_dana = Transaksi::where('id_user', $id_user_login)
            ->where('status', 'Selesai')
            ->where('created_at', '>=', now()->subYear())->sum('total_harga');

        return view('profile.index', compact(
            'user', 'riwayat_penjualan', 'riwayat_pembelian',
            'sales_1_bulan_barang', 'sales_1_bulan_dana', 'sales_1_tahun_barang', 'sales_1_tahun_dana',
            'buy_1_bulan_barang', 'buy_1_bulan_dana', 'buy_1_tahun_barang', 'buy_1_tahun_dana'
        ));
    }
}