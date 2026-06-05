@extends('layouts.app')
@section('title', 'Konfirmasi Pembelian')
@section('content')
<div class="max-w-xl mx-auto bg-white border border-slate-200 rounded-3xl p-8 shadow-sm">
    <h1 class="text-xl font-bold text-slate-800 mb-6 flex items-center gap-2"><i class="fas fa-shopping-cart text-blue-600"></i> Konfirmasi Pesanan</h1>
    
    <div class="flex gap-4 border-b border-slate-100 pb-6 mb-6">
        <img src="{{ asset('assets/img/'.$barang->gambar) }}" class="w-20 h-20 object-cover rounded-xl" alt="">
        <div>
            <h3 class="font-bold text-slate-800 text-sm">{{ $barang->nama_barang }}</h3>
            <p class="text-xs text-slate-400 mt-0.5">Penjual: {{ $barang->penjual->username }}</p>
            <p class="text-blue-600 font-extrabold text-sm mt-2">Rp {{ number_format($barang->harga, 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="bg-slate-50 p-4 rounded-xl space-y-2 mb-6 text-xs">
        <div class="flex justify-between text-slate-500"><span>Harga Satuan</span><span>Rp {{ number_format($barang->harga, 0, ',', '.') }}</span></div>
        <div class="flex justify-between text-slate-500"><span>Jumlah Pembelian</span><span>1 Unit</span></div>
        <hr class="border-slate-200 my-2">
        <div class="flex justify-between text-slate-800 font-bold text-sm"><span>Total Tagihan</span><span>Rp {{ number_format($barang->harga, 0, ',', '.') }}</span></div>
    </div>

    <form action="{{ route('barang.proses_bayar', $barang->id_barang) }}" method="POST">
        @csrf
        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 rounded-xl shadow-lg transition text-sm flex items-center justify-center gap-2">
            <i class="fas fa-lock"></i> Konfirmasi & Kunci Barang
        </button>
    </form>
</div>
@endsection