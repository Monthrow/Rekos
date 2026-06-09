@extends('admin.layout')

@section('title', 'Dashboard Admin')

@section('content')
<h1 class="text-2xl font-black mb-8">Statistik Kinerja Rekos</h1>

<div class="grid grid-cols-2 md:grid-cols-5 gap-6 mb-8">
    <div class="bg-slate-950 border border-slate-800 p-6 rounded-2xl">
        <p class="text-xs text-slate-400 font-bold uppercase">Total Penjual</p>
        <p class="text-3xl font-black text-blue-500 mt-2">{{ $total_penjual }}</p>
    </div>
    <div class="bg-slate-950 border border-slate-800 p-6 rounded-2xl">
        <p class="text-xs text-slate-400 font-bold uppercase">Total Pembeli</p>
        <p class="text-3xl font-black text-emerald-500 mt-2">{{ $total_pembeli }}</p>
    </div>
    <div class="bg-slate-950 border border-slate-800 p-6 rounded-2xl">
        <p class="text-xs text-slate-400 font-bold uppercase">Total Barang</p>
        <p class="text-3xl font-black text-purple-500 mt-2">{{ $total_barang }}</p>
    </div>
    <div class="bg-slate-950 border border-slate-800 p-6 rounded-2xl">
        <p class="text-xs text-slate-400 font-bold uppercase">Total Transaksi</p>
        <p class="text-3xl font-black text-amber-500 mt-2">{{ $total_transaksi }}</p>
    </div>
    <a href="{{ route('admin.pendaftaran') }}" class="bg-red-950 border border-red-800 p-6 rounded-2xl hover:bg-red-900 transition">
        <p class="text-xs text-red-400 font-bold uppercase">Menunggu Approval</p>
        <p class="text-3xl font-black text-red-400 mt-2">{{ $total_pendaftar }}</p>
        <p class="text-xs text-red-500 mt-1">Pendaftar penjual baru</p>
    </a>
</div>

{{-- Tombol Report --}}
<div class="bg-slate-950 border border-slate-800 rounded-2xl p-6 mb-8">
    <h3 class="font-bold text-sm text-slate-300 mb-4">Laporan Penjualan</h3>
    <div class="flex gap-4">
        <a href="{{ route('report.admin.pdf') }}" target="_blank" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition">
            Download PDF
        </a>
        <a href="{{ route('report.admin.excel') }}" target="_blank" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-xl text-xs font-bold transition">
            Download Excel
        </a>
    </div>
</div>

{{-- Postingan Produk Terbaru --}}
<div class="bg-slate-950 border border-slate-800 rounded-2xl p-6">
    <h3 class="font-bold text-sm text-slate-300 mb-4">Postingan Produk Terbaru</h3>
    <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
        @forelse($barang_terbaru as $b)
            <div class="bg-slate-900 border border-slate-800 rounded-xl overflow-hidden text-xs">
                <img src="{{ asset('assets/img/'.$b->gambar) }}" class="h-32 w-full object-cover" alt="">
                <div class="p-3">
                    <p class="font-bold line-clamp-1 text-slate-200">{{ $b->nama_barang }}</p>
                    <p class="text-blue-400 font-extrabold mt-1">Rp {{ number_format($b->harga, 0, ',', '.') }}</p>
                    <p class="text-[10px] text-slate-500 mt-2"><i class="fas fa-user mr-1"></i> {{ $b->penjual->username }}</p>
                </div>
            </div>
        @empty
            <p class="col-span-full text-center text-slate-500 py-6">Belum ada barang terupload.</p>
        @endforelse
    </div>
</div>
@endsection