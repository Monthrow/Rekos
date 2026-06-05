@extends('layouts.app')
@section('title', 'Katalog Barang Kos')
@section('content')
<div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 mb-8">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Cari Kebutuhan Kosmu</h1>
        <p class="text-sm text-slate-500">Katalog barang bekas berkualitas tinggi harga mahasiswa.</p>
    </div>
    <form action="{{ route('dashboard') }}" method="GET" class="w-full md:w-auto flex gap-2">
        <input type="text" name="q" value="{{ $keyword }}" placeholder="Cari kipas, kasur, lemari..." class="px-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-400 outline-none text-sm w-full md:w-64">
        <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded-xl text-sm font-semibold hover:bg-blue-700 transition">Cari</button>
    </form>
</div>

@if(session('success'))
    <div class="mb-6 p-4 bg-emerald-100 text-emerald-800 rounded-xl font-medium text-sm border border-emerald-200 shadow-sm">{{ session('success') }}</div>
@endif

<div class="grid grid-cols-2 md:grid-cols-4 gap-6">
    @forelse($barangs as $b)
        <div class="bg-white border border-slate-100 rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition flex flex-col">
            <img src="{{ asset('assets/img/'.$b->gambar) }}" class="h-44 w-full object-cover" alt="{{ $b->nama_barang }}">
            <div class="p-4 flex flex-col flex-1">
                <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 bg-emerald-50 text-emerald-600 rounded-md self-start mb-2">{{ $b->status_barang }}</span>
                <h3 class="font-bold text-slate-800 line-clamp-1 mb-1 text-sm">{{ $b->nama_barang }}</h3>
                <p class="text-blue-600 font-extrabold text-base mb-4">Rp {{ number_format($b->harga, 0, ',', '.') }}</p>
                <a href="{{ route('barang.detail', $b->id_barang) }}" class="mt-auto block text-center py-2.5 bg-slate-50 hover:bg-blue-600 hover:text-white rounded-xl text-xs font-bold transition text-slate-700">Lihat Detail</a>
            </div>
        </div>
    @empty
        <div class="col-span-full bg-white rounded-2xl border border-slate-200 text-center py-16 text-slate-400">
            <i class="fas fa-store-slash text-5xl mb-4 text-slate-300"></i>
            <h3 class="text-base font-bold text-slate-700">Barang Tidak Ditemukan</h3>
            <p class="text-sm text-slate-500 mt-1">Gunakan kata kunci pencarian yang lain.</p>
        </div>
    @endforelse
</div>
@endsection