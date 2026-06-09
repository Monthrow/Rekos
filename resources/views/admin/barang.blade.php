@extends('admin.layout')

@section('title', 'Kelola Barang')

@section('content')
    {{-- Header Konten --}}
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-2xl font-black">Database Barang Pengguna</h1>
        <form action="{{ route('admin.barang') }}" method="GET" class="flex gap-2">
            <input type="text" name="q" value="{{ $keyword }}" placeholder="Cari nama barang..." class="px-4 py-2 bg-slate-950 border border-slate-800 rounded-xl focus:outline-none focus:border-blue-500 text-xs w-64">
            <button type="submit" class="px-4 py-2 bg-blue-600 font-bold text-xs rounded-xl hover:bg-blue-700 transition">Filter</button>
        </form>
    </div>

    {{-- Tabel Data --}}
    <div class="bg-[#0B132B] border border-slate-800/60 rounded-2xl overflow-hidden text-xs shadow-sm">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-900/50 text-slate-400 border-b border-slate-800/60">
                    <th class="p-4">Foto</th>
                    <th class="p-4">Nama Produk</th>
                    <th class="p-4">Penjual</th>
                    <th class="p-4">Harga</th>
                    <th class="p-4">Stok</th>
                    <th class="p-4">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800/40">
                @forelse($barang as $b)
                <tr class="hover:bg-slate-900/30 transition">
                    <td class="p-4"><img src="{{ asset('assets/img/'.$b->gambar) }}" class="w-12 h-12 object-cover rounded-lg" alt=""></td>
                    <td class="p-4 font-bold text-slate-200">{{ $b->nama_barang }}</td>
                    <td class="p-4"><span class="px-2 py-0.5 bg-slate-900 border border-slate-800 text-slate-400 rounded-md">{{ $b->penjual->username }}</span></td>
                    <td class="p-4 font-extrabold text-blue-400">Rp {{ number_format($b->harga, 0, ',', '.') }}</td>
                    <td class="p-4 text-slate-400">{{ $b->jumlah }} unit</td>
                    <td class="p-4 uppercase tracking-wider font-bold text-[10px] {{ $b->status_barang == 'terjual' ? 'text-red-400' : 'text-emerald-400' }}">{{ $b->status_barang }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="p-12 text-center text-slate-500">
                        <i class="fas fa-box-open text-2xl mb-2 block text-slate-600"></i>
                        Data produk masih kosong.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection