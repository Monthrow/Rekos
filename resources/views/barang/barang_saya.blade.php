@extends('layouts.app')
@section('title', 'Barang Dagangan Saya')
@section('content')
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Manajemen Barang Toko</h1>
        <p class="text-sm text-slate-500">Kelola status penawaran produk atau edit data barang.</p>
    </div>
    <a href="{{ route('barang.jual') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm rounded-xl transition"><i class="fas fa-plus mr-2"></i>Tambah Barang</a>
</div>

@if(session('success'))
    <div class="mb-6 p-4 bg-emerald-100 text-emerald-800 rounded-xl font-medium text-sm border border-emerald-200 shadow-sm">{{ session('success') }}</div>
@endif

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    @forelse($barangs as $b)
        <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden flex flex-col shadow-sm">
            <img src="{{ asset('assets/img/'.$b->gambar) }}" class="h-44 w-full object-cover" alt="">
            <div class="p-4 flex flex-col flex-1">
                <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 {{ $b->status_barang == 'terjual' ? 'bg-red-50 text-red-600' : ($b->status_barang == 'pending' ? 'bg-amber-50 text-amber-600' : 'bg-emerald-50 text-emerald-600') }} rounded-md self-start mb-2">{{ $b->status_barang }}</span>
                <h3 class="font-bold text-slate-800 text-sm mb-1 line-clamp-1">{{ $b->nama_barang }}</h3>
                <p class="text-blue-600 font-extrabold text-sm mb-4">Rp {{ number_format($b->harga, 0, ',', '.') }}</p>
                
                <div class="grid grid-cols-2 gap-2 mt-auto pt-2 border-t border-slate-100">
                    <a href="{{ route('barang.edit', $b->id_barang) }}" class="text-center py-2 bg-slate-50 hover:bg-slate-100 rounded-xl text-xs font-bold border border-slate-200">Edit Data</a>
                    @if($b->status_barang === 'pending')
                        <a href="{{ route('barang.konfirmasi', $b->id_barang) }}" class="text-center py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold">Selesai</a>
                    @else
                        <form action="{{ route('barang.hapus') }}" method="POST" onsubmit="return confirm('Hapus permanen barang ini?')">
                            @csrf
                            <input type="hidden" name="hapus_id" value="{{ $b->id_barang }}">
                            <button type="submit" class="w-full py-2 bg-red-50 hover:bg-red-100 text-red-600 font-bold rounded-xl text-xs border border-red-200">Hapus</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="col-span-full bg-white rounded-2xl border border-slate-200 text-center py-12 text-slate-400">Belum ada barang yang Anda jual.</div>
    @endforelse
</div>
@endsection