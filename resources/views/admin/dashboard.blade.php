@extends('admin.layout')

@section('title', 'Dashboard Admin')

@section('content')
<h1 class="text-2xl font-black mb-8 text-white">Statistik Kinerja Rekos</h1>

{{-- Kartu Statistik Utama --}}
<div class="grid grid-cols-2 md:grid-cols-5 gap-6 mb-8">
    <div class="bg-[#0B132B] border border-slate-800/60 p-6 rounded-2xl shadow-sm">
        <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Total Penjual</p>
        <p class="text-4xl font-black text-blue-500 mt-2">{{ $total_penjual }}</p>
    </div>
    
    <div class="bg-[#0B132B] border border-slate-800/60 p-6 rounded-2xl shadow-sm">
        <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Total Pembeli</p>
        <p class="text-4xl font-black text-emerald-500 mt-2">{{ $total_pembeli }}</p>
    </div>
    
    <div class="bg-[#0B132B] border border-slate-800/60 p-6 rounded-2xl shadow-sm">
        <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Total Barang</p>
        <p class="text-4xl font-black text-purple-500 mt-2">{{ $total_barang }}</p>
    </div>
    
    <div class="bg-[#0B132B] border border-slate-800/60 p-6 rounded-2xl shadow-sm">
        <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Total Transaksi</p>
        <p class="text-4xl font-black text-amber-500 mt-2">{{ $total_transaksi }}</p>
    </div>
    
    <a href="{{ route('admin.pendaftaran') }}" class="bg-red-950/40 border border-red-900/40 p-6 rounded-2xl hover:bg-red-900/30 transition shadow-sm group">
        <p class="text-xs text-red-400 font-bold uppercase tracking-wider group-hover:text-red-300">Menunggu Approval</p>
        <p class="text-4xl font-black text-red-500 mt-2 group-hover:text-red-400">{{ $total_pendaftar }}</p>
        <p class="text-[11px] text-red-500/80 mt-1 group-hover:text-red-400/80">Pendaftar penjual baru</p>
    </a>
</div>

{{-- Blok Laporan Penjualan --}}
<div class="bg-[#0B132B] border border-slate-800/60 rounded-2xl p-6 mb-8 shadow-sm">
    <h3 class="font-bold text-sm text-slate-300 mb-4 tracking-wide">Laporan Penjualan</h3>
    <div class="flex gap-4">
        <a href="{{ route('report.admin.pdf') }}" target="_blank" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition shadow-lg shadow-blue-600/20">
            Download PDF
        </a>
        <a href="{{ route('report.admin.excel') }}" target="_blank" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition shadow-lg shadow-emerald-600/20">
            Download Excel
        </a>
    </div>
</div>

{{-- Postingan Produk Terbaru --}}
<div class="bg-[#0B132B] border border-slate-800/60 rounded-2xl p-6 shadow-sm">
    <h3 class="font-bold text-sm text-slate-300 mb-4 tracking-wide">Postingan Produk Terbaru</h3>
    <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
        @forelse($barang_terbaru as $b)
            <div class="bg-slate-900/40 border border-slate-800/60 rounded-xl overflow-hidden text-xs flex flex-col justify-between transition hover:border-slate-700">
                <img src="{{ asset('assets/img/'.$b->gambar) }}" class="h-36 w-full object-cover" alt="{{ $b->nama_barang }}">
                <div class="p-4 flex-1 flex flex-col justify-between">
                    <div>
                        <p class="font-bold line-clamp-1 text-slate-200 text-sm">{{ $b->nama_barang }}</p>
                        <p class="text-blue-400 font-extrabold text-sm mt-1">Rp {{ number_format($b->harga, 0, ',', '.') }}</p>
                    </div>
                    <p class="text-[10px] text-slate-500 mt-3 flex items-center gap-1.5 pt-2 border-t border-slate-800/40">
                        <i class="fas fa-user text-slate-600"></i> 
                        <span class="truncate">{{ $b->penjual->username }}</span>
                    </p>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center text-slate-500 py-12 bg-slate-900/20 rounded-xl border border-dashed border-slate-800/60">
                <i class="fas fa-box-open text-2xl mb-2 block text-slate-600"></i>
                Belum ada barang terupload.
            </div>
        @endforelse
    </div>
</div>
@endsection