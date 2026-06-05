@extends('layouts.app')
@section('title', $barang->nama_barang)
@section('content')

<div class="flex flex-col lg:flex-row gap-6 max-w-7xl mx-auto px-4 py-2 text-slate-700 antialiased">
    
    <!-- ==========================================
         PANEL DETAIL PRODUK (KIRI)
         ========================================== -->
    <div class="flex-1 bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex flex-col">
        <div class="mb-4">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-semibold text-blue-600 hover:text-blue-700 transition">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Dashboard
            </a>
        </div>

        <div class="w-full bg-slate-50 rounded-2xl overflow-hidden flex items-center justify-center p-4 mb-6">
            <img src="{{ asset('assets/img/'.$barang->gambar) }}" class="max-h-80 object-contain rounded-xl" alt="{{ $barang->nama_barang }}">
        </div>

        <h1 class="text-3xl font-bold text-slate-800 mb-1">{{ $barang->nama_barang }}</h1>
        
        <div class="flex flex-wrap items-center justify-between gap-4 mb-4">
            <p class="text-3xl font-black text-blue-600">Rp {{ number_format($barang->harga, 0, ',', '.') }}</p>
            
            <!-- Badge Status Barang -->
            <div>
                @if($barang->status_barang === 'tersedia')
                    <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full uppercase">Tersedia</span>
                @elseif($barang->status_barang === 'pending')
                    <span class="px-3 py-1 bg-amber-100 text-amber-700 text-xs font-bold rounded-full uppercase">Booked (Pending)</span>
                @else
                    <span class="px-3 py-1 bg-red-100 text-red-700 text-xs font-bold rounded-full uppercase">Terjual</span>
                @endif
            </div>
        </div>
        
        <div class="flex items-center text-xs font-semibold text-slate-500 gap-2 mb-6 bg-slate-50 px-3 py-2 rounded-lg self-start">
            <i class="fas fa-cubes text-blue-500"></i>
            <span>Stok: <strong class="text-slate-700">{{ $barang->jumlah }} unit</strong></span>
        </div>

        <!-- ==========================================
             LOGIKA TOMBOL AKSI & TIMER COUNTDOWN
             ========================================== -->
        <div class="mb-6">
            @if($id_user_login !== $id_penjual)
                <!-- TAMPILAN UNTUK PEMBELI (Misal: Nayyara) -->
                @if($barang->status_barang === 'tersedia')
                    <form action="{{ route('barang.bayar', $barang->id_barang) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-xl transition shadow-md hover:shadow-lg flex items-center justify-center gap-2 text-sm">
                            <i class="fas fa-shopping-cart"></i> Beli Sekarang
                        </button>
                    </form>
                @elseif($barang->status_barang === 'pending' && $barang->id_pembeli == $id_user_login)
                    <!-- Tampilan Box Timer Pembeli -->
                    <div class="p-5 bg-gradient-to-br from-amber-50 to-orange-50 border border-amber-200 rounded-2xl shadow-sm text-center">
                        <p class="text-xs font-bold text-amber-800 uppercase tracking-wider mb-2">
                            <i class="fas fa-clock animate-pulse mr-1 text-orange-500"></i> Sisa Waktu Booking Anda
                        </p>
                        
                        <!-- Angka Timer Digital -->
                        <div class="text-3xl font-black text-slate-800 tracking-widest my-2 bg-white inline-block px-6 py-2 rounded-xl border border-amber-200/60 shadow-inner" id="countdownContainer">
                            <span id="timerMin">00</span>:<span id="timerSec">00</span>
                        </div>
                        
                        <p class="text-[11px] text-slate-500 mt-2 max-w-sm mx-auto leading-relaxed">
                            Segera hubungi penjual melalui kolom chat di samping kanan untuk menyelesaikan pembayaran transaksi sebelum batas waktu habis.
                        </p>
                    </div>
                @elseif($barang->status_barang === 'pending')
                    <button disabled class="w-full bg-slate-200 text-slate-400 font-bold py-3 px-6 rounded-xl text-sm cursor-not-allowed">
                        Barang Sedang Di-booking Orang Lain
                    </button>
                @else
                    <button disabled class="w-full bg-slate-200 text-slate-400 font-bold py-3 px-6 rounded-xl text-sm cursor-not-allowed">
                        Barang Sudah Terjual
                    </button>
                @endif
            @else
                <!-- TAMPILAN UNTUK PENJUAL (ArifDhuha) -->
                @if($barang->status_barang === 'pending')
                    @php
                        $pembeli_booking = $daftar_inbox->firstWhere('id_pembeli', $barang->id_pembeli);
                        $nama_pembeli = $pembeli_booking ? $pembeli_booking->username : 'User #' . $barang->id_pembeli;
                    @endphp

                    <!-- Box Status, Nama Pembeli, dan Sisa Timer di Sisi Penjual -->
                    <div class="mb-4 p-5 bg-gradient-to-br from-slate-50 to-amber-50/40 border border-amber-200 rounded-2xl shadow-sm">
                        <div class="flex items-center justify-between border-b border-amber-100 pb-3 mb-3">
                            <div class="flex items-center gap-2 font-bold text-amber-800 text-xs uppercase tracking-wider">
                                <i class="fas fa-exclamation-triangle text-amber-600 animate-bounce"></i>
                                <span>Sedang Di-booking</span>
                            </div>
                            
                            <!-- Timer Mini untuk Penjual -->
                            <div class="bg-slate-900 text-white text-xs font-mono font-bold px-2.5 py-1 rounded-lg tracking-wider shadow-sm" id="countdownContainer">
                                <i class="far fa-clock mr-1 text-amber-400"></i><span id="timerMin">00</span>:<span id="timerSec">00</span>
                            </div>
                        </div>

                        <p class="text-xs text-slate-600 leading-relaxed">
                            Barang ini sedang di-booking oleh <strong class="text-slate-950 bg-amber-200 px-1.5 py-0.5 rounded font-bold">@ {{ $nama_pembeli }}</strong>. 
                            Sistem memberikan waktu 10 menit kepada pembeli. Jika waktu di atas habis, status booking otomatis batal.
                        </p>
                    </div>
                    
                    <form action="{{ route('barang.konfirmasi', $barang->id_barang) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-xl transition shadow-md flex items-center justify-center gap-2 text-sm">
                            <i class="fas fa-check-circle"></i> Konfirmasi Transaksi Selesai (Laku ke {{ $nama_pembeli }})
                        </button>
                    </form>
                @endif
            @endif
        </div>

        <div class="bg-slate-50/50 rounded-xl p-4 border border-slate-100 flex-1">
            <h4 class="font-bold text-sm text-slate-800 mb-2">Deskripsi</h4>
            <p class="text-slate-600 text-sm leading-relaxed whitespace-pre-line">{{ $barang->deskripsi }}</p>
        </div>
    </div>

    <!-- ==========================================
         RUANG OBROLAN CHAT (KANAN)
         ========================================== -->
    <div class="w-full lg:w-[420px] flex flex-col gap-4">
        
        <!-- DAFTAR CHAT MASUK (Khusus Penjual) -->
        @if($id_user_login === $id_penjual && isset($daftar_inbox) && $daftar_inbox->isNotEmpty())
            <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-sm">
                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">
                    <i class="fas fa-inbox mr-1 text-blue-500"></i> Daftar Chat Masuk
                </h4>
                <div class="flex flex-wrap gap-2">
                    @foreach($daftar_inbox as $inbox)
                        <a href="{{ route('barang.detail', ['id' => $barang->id_barang, 'buyer_id' => $inbox->id_pembeli]) }}" 
                           class="inline-flex items-center gap-2 px-3 py-2 rounded-xl text-xs font-bold transition border 
                           {{ $chat_buyer_id == $inbox->id_pembeli 
                               ? 'bg-blue-600 text-white border-blue-600 shadow-sm' 
                               : 'bg-slate-50 text-slate-700 hover:bg-slate-100 border-slate-200' }}">
                            <div class="w-5 h-5 rounded-full bg-blue-500/20 text-blue-600 flex items-center justify-center text-[10px] uppercase {{ $chat_buyer_id == $inbox->id_pembeli ? 'bg-white/20 text-white' : '' }}">
                                {{ substr($inbox->username, 0, 1) }}
                            </div>
                            <span>{{ $inbox->username }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- BOX UTAMA RUANG CHAT -->
        @if(isset($show_chat) && $show_chat)
            <div class="bg-white border border-slate-100 rounded-3xl shadow-md overflow-hidden flex flex-col h-[550px]">
                
                <!-- Header Chat -->
                <div class="bg-blue-600 px-5 py-4 flex items-center justify-between text-white shadow-inner">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center font-bold text-sm uppercase border border-white/10 shadow-sm">
                            @if($id_user_login === $id_penjual)
                                {{ substr($daftar_inbox->firstWhere('id_pembeli', $target_pembeli)->username ?? 'Buyer', 0, 1) }}
                            @else
                                {{ substr($barang->penjual->username, 0, 1) }}
                            @endif
                        </div>
                        <div>
                            <h3 class="font-bold text-sm leading-tight">
                                @if($id_user_login === $id_penjual)
                                    {{ $daftar_inbox->firstWhere('id_pembeli', $target_pembeli)->username ?? 'User #'.$target_pembeli }}
                                @else
                                    {{ $barang->penjual->username }}
                                @endif
                            </h3>
                            <span class="inline-flex items-center text-[10px] bg-amber-400 text-slate-900 px-1.5 py-0.5 rounded-full font-bold mt-1">
                                <span class="w-1.5 h-1.5 bg-slate-900 rounded-full mr-1 animate-pulse"></span> Tanya Jawab
                            </span>
                        </div>
                    </div>
                    <i class="fas fa-comments text-xl opacity-80"></i>
                </div>

                <!-- Konten Alur Percakapan -->
                <div class="flex-1 overflow-y-auto p-4 bg-slate-50/40 space-y-4" id="chatBox">
                    @forelse($pesans as $p)
                        @php 
                            $is_me = ((int)$p->id_pengirim === (int)$id_user_login); 
                        @endphp
                        
                        <div class="flex flex-col {{ $is_me ? 'items-end' : 'items-start' }}">
                            <div class="p-3.5 max-w-[80%] shadow-sm rounded-2xl text-sm leading-relaxed 
                                {{ $is_me 
                                    ? 'bg-blue-600 text-white rounded-tr-none' 
                                    : 'bg-white text-slate-800 border border-slate-100 rounded-tl-none' 
                                }}">
                                <p>{{ $p->isi_pesan }}</p>
                            </div>
                            <span class="text-[9px] text-slate-400 mt-1 px-1">
                                {{ $p->waktu_kirim ?? 'Hari ini' }}
                            </span>
                        </div>
                    @empty
                        <div class="h-full flex flex-col items-center justify-center text-slate-400 py-12">
                            <i class="far fa-comment-dots text-3xl mb-2 text-slate-300"></i>
                            <p class="text-xs">Belum ada obrolan. Ketik pesan untuk memulai.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Form Input Kirim Pesan -->
                <div class="p-4 border-t border-slate-100 bg-white">
                    <form action="{{ route('barang.chat', ['id' => $barang->id_barang, 'buyer_id' => $chat_buyer_id]) }}" method="POST" class="flex items-center gap-2">
                        @csrf
                        <div class="flex-1 relative">
                            <input type="text" name="isi_pesan" required autocomplete="off" placeholder="Ketik pesan..." 
                                   class="w-full pl-4 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-xs transition placeholder:text-slate-400">
                        </div>
                        <button type="submit" class="w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center hover:bg-blue-700 transition shadow-md flex-shrink-0">
                            <i class="fas fa-paper-plane text-xs"></i>
                        </button>
                    </form>
                </div>

            </div>
        @else
            <div class="w-full bg-slate-50 border border-dashed border-slate-200 rounded-3xl p-6 flex flex-col items-center justify-center text-center h-[550px]">
                <i class="fas fa-inbox text-4xl text-slate-300 mb-3"></i>
                <h4 class="font-bold text-sm text-slate-700 mb-1">Ruang Obrolan Kosong</h4>
                <p class="text-xs text-slate-400 max-w-[240px] leading-relaxed">
                    @if($id_user_login === $id_penjual)
                        Silakan pilih salah satu daftar pembeli di atas untuk memulai negosiasi barang ini.
                    @else
                        Belum ada pesan yang dikirimkan.
                    @endif
                </p>
            </div>
        @endif
    </div>
</div>

<!-- ==========================================
     JAVASCRIPT LOGIC
     ========================================== -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Auto Scroll Ruang Obrolan ke paling bawah
        const box = document.getElementById('chatBox');
        if (box) {
            box.scrollTop = box.scrollHeight;
        }

        // Logic Hitung Mundur Otomatis untuk Pembeli maupun Penjual
        @if(isset($waktu_habis) && $waktu_habis > 0)
            const targetTimestamp = {{ $waktu_habis }} * 1000; 

            function updateCountdown() {
                const sekarang = new Date().getTime();
                const selisih = targetTimestamp - sekarang;

                if (selisih <= 0) {
                    clearInterval(timerInterval);
                    document.getElementById('countdownContainer').innerHTML = "<span class='text-red-500 text-[10px] font-bold uppercase'>Habis</span>";
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                    return;
                }

                const menit = Math.floor((selisih % (1000 * 60 * 60)) / (1000 * 60));
                const detik = Math.floor((selisih % (1000 * 60)) / 1000);

                document.getElementById('timerMin').innerText = menit < 10 ? '0' + menit : menit;
                document.getElementById('timerSec').innerText = detik < 10 ? '0' + detik : detik;
            }

            updateCountdown(); 
            const timerInterval = setInterval(updateCountdown, 1000);
        @endif
    });
</script>
@endsection