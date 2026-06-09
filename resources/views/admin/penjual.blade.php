@extends('admin.layout')

@section('title', 'Kelola Penjual')

@section('content')
    <h1 class="text-2xl font-black mb-8">Database Akun Merchant / Penjual</h1>
    
    @if(session('success'))
        <div class="mb-4 p-3 bg-emerald-500/20 text-emerald-200 border border-emerald-500/30 rounded-xl text-xs font-semibold">
            {{ session('success') }}
        </div>
    @endif

    {{-- Pesan jika belum ada penjual --}}
    @if($penjual->isEmpty())
        <div class="bg-[#0B132B] border border-slate-800/60 rounded-2xl p-6 mb-6 text-center">
            <i class="fas fa-info-circle text-blue-400 text-2xl mb-2"></i>
            <p class="text-slate-400 font-semibold">Belum ada penjual yang terdaftar saat ini.</p>
        </div>
    @endif

    <div class="bg-[#0B132B] border border-slate-800/60 rounded-2xl overflow-hidden text-xs shadow-sm">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-900/50 text-slate-400 border-b border-slate-800/60">
                    <th class="p-4">Username</th>
                    <th class="p-4">Email Kampus</th>
                    <th class="p-4">No. Telepon</th>
                    <th class="p-4">Alamat Kos / Rumah</th>
                    <th class="p-4 text-center">Jumlah Produk</th>
                    <th class="p-4 text-center">Aksi Manajemen</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800/40">
                @forelse($penjual as $p)
                <tr class="hover:bg-slate-900/30 transition">
                    <td class="p-4 font-bold text-slate-200">{{ $p->username }}</td>
                    <td class="p-4 text-slate-400">{{ $p->email }}</td>
                    <td class="p-4 text-slate-300">{{ $p->no_telp }}</td>
                    <td class="p-4 text-slate-400 max-w-xs truncate">{{ $p->alamat }}</td>
                    <td class="p-4 text-center font-bold text-blue-400">{{ $p->barangs_count ?? $p->barang_count ?? 0 }} unit</td>
                    <td class="p-4">
                        <div class="flex gap-2 justify-center">
                            <a href="{{ route('admin.barang', ['id_user' => $p->id_user]) }}" class="px-3 py-1.5 bg-blue-600/20 text-blue-400 border border-blue-500/20 hover:bg-blue-600 hover:text-white transition rounded-lg font-bold text-[11px]">
                                Lihat Barang
                            </a>
                            <form action="{{ route('admin.penjual.hapus') }}" method="POST" onsubmit="return confirm('Hapus penjual beserta seluruh barang miliknya?')">
                                @csrf
                                <input type="hidden" name="id_user" value="{{ $p->id_user }}">
                                <button type="submit" class="px-3 py-1.5 bg-red-600/20 text-red-400 border border-red-500/20 hover:bg-red-600 hover:text-white transition rounded-lg font-bold text-[11px]">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                    @if(!$penjual->isEmpty())
                    <tr>
                        <td colspan="6" class="p-12 text-center text-slate-500">
                            <i class="fas fa-store-slash text-2xl mb-2 block text-slate-600"></i>
                            Data penjual tidak ditemukan.
                        </td>
                    </tr>
                    @endif
                @endforelse
            </tbody>
        </table>
    </div>
@endsection