@extends('admin.layout')

@section('title', 'Pendaftaran Penjual - Rekos Admin')

@section('content')
    <h1 class="text-2xl font-black mb-2">Pendaftaran Penjual Baru</h1>
    <p class="text-slate-400 text-sm mb-8">Setujui atau tolak pendaftaran dalam 24 jam. Lewat dari itu otomatis ditolak.</p>

    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-900/50 text-emerald-400 rounded-2xl font-semibold flex items-center gap-2 border border-emerald-700">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <div class="bg-[#0B132B] border border-slate-800/60 rounded-2xl overflow-hidden shadow-sm text-xs">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-900/50 border-b border-slate-800/60 text-slate-400 text-[11px] uppercase tracking-wider">
                    <th class="p-4">Nama</th>
                    <th class="p-4">Email</th>
                    <th class="p-4">No. HP</th>
                    <th class="p-4">Alamat</th>
                    <th class="p-4">Daftar Sejak</th>
                    <th class="p-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800/40">
                @forelse($pendaftar as $user)
                    <tr class="hover:bg-slate-900/30 transition">
                        <td class="p-4 font-bold text-white">{{ $user->username }}</td>
                        <td class="p-4 text-slate-400">{{ $user->email }}</td>
                        <td class="p-4 text-slate-300">{{ $user->no_telp ?? '-' }}</td>
                        <td class="p-4 text-slate-300 max-w-xs truncate">{{ $user->alamat ?? '-' }}</td>
                        <td class="p-4">
                            <span class="text-slate-300 font-medium">
                                {{ $user->tgl_daftar_penjual ? \Carbon\Carbon::parse($user->tgl_daftar_penjual)->format('d M Y, H:i') : '-' }}
                            </span>
                            @if($user->tgl_daftar_penjual)
                                @php $sisa = \Carbon\Carbon::parse($user->tgl_daftar_penjual)->addHours(24)->diffForHumans() @endphp
                                <div class="text-[10px] text-amber-400 font-semibold mt-0.5 flex items-center gap-1">
                                    <i class="fas fa-clock"></i> Batas: {{ $sisa }}
                                </div>
                            @endif
                        </td>
                        <td class="p-4">
                            <div class="flex gap-2 justify-center">
                                <form action="{{ route('admin.approve') }}" method="POST" class="m-0">
                                    @csrf
                                    <input type="hidden" name="id_user" value="{{ $user->id_user }}">
                                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold px-3 py-1.5 rounded-xl transition shadow-sm shadow-emerald-900/20">
                                        <i class="fas fa-check mr-1"></i> Setujui
                                    </button>
                                </form>
                                <form action="{{ route('admin.tolak') }}" method="POST" class="m-0">
                                    @csrf
                                    <input type="hidden" name="id_user" value="{{ $user->id_user }}">
                                    <button type="submit" class="bg-red-600/20 hover:bg-red-600 text-red-400 hover:text-white text-xs font-bold px-3 py-1.5 rounded-xl transition border border-red-500/20">
                                        <i class="fas fa-times mr-1"></i> Tolak
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center p-12 text-slate-500">
                            <i class="fas fa-check-circle text-3xl block mb-3 text-slate-700"></i>
                            <span class="font-medium text-slate-400">Tidak ada pendaftaran baru.</span>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection