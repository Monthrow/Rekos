@extends('layouts.app')
@section('title', 'Konfirmasi Pembelian')
@section('content')

<div class="max-w-2xl mx-auto px-4 py-8">
    
    <!-- Header -->
    <div class="mb-6">
        <a href="{{ route('barang.detail', $barang->id_barang) }}" class="inline-flex items-center text-sm font-semibold text-blue-600 hover:text-blue-700 transition">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Detail Barang
        </a>
        <h1 class="text-2xl font-bold text-slate-800 mt-3">Konfirmasi Pembelian</h1>
        <p class="text-sm text-slate-500 mt-1">Periksa detail barang sebelum melanjutkan ke pembayaran.</p>
    </div>

    <!-- Card Info Barang -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 mb-5">
        <div class="flex items-center gap-4">
            @if($barang->gambar)
                <img src="{{ asset('assets/img/'.$barang->gambar) }}" 
                     class="w-20 h-20 object-cover rounded-xl border border-slate-100" 
                     alt="{{ $barang->nama_barang }}">
            @else
                <div class="w-20 h-20 bg-slate-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-box text-slate-400 text-2xl"></i>
                </div>
            @endif
            <div class="flex-1">
                <h2 class="text-lg font-bold text-slate-800">{{ $barang->nama_barang }}</h2>
                <p class="text-2xl font-black text-blue-600 mt-1">Rp {{ number_format($barang->harga, 0, ',', '.') }}</p>
                <p class="text-xs text-slate-500 mt-1">
                    <i class="fas fa-user mr-1"></i> Penjual: <strong>{{ $barang->penjual->username }}</strong>
                </p>
            </div>
        </div>
    </div>

    <!-- Form Jumlah Beli -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 mb-5">
        <form action="{{ route('transaksi.proses', $barang->id_barang) }}" method="POST">
            @csrf
            <h3 class="text-sm font-bold text-slate-700 mb-4">Detail Pembelian</h3>
            
            <div class="mb-4">
                <label class="block text-sm font-semibold text-slate-600 mb-2">Jumlah Beli</label>
                <input type="number" name="jumlah_beli" 
                       class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-sm transition"
                       value="1" min="1" max="{{ $barang->jumlah }}" required>
                <p class="text-xs text-slate-400 mt-1.5">
                    <i class="fas fa-cubes mr-1 text-blue-400"></i> Stok tersedia: <strong>{{ $barang->jumlah }} unit</strong>
                </p>
            </div>

            <button type="submit" 
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-xl transition shadow-md hover:shadow-lg flex items-center justify-center gap-2 text-sm">
                <i class="fas fa-arrow-right"></i> Lanjut ke Pembayaran
            </button>
        </form>
    </div>

</div>

@endsection